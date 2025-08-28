<?php

declare(strict_types=1);

namespace App\UI\Auth;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Accounts;
use App\Model\Email;

final class AuthPresenter extends Nette\Application\UI\Presenter
{

	private $id;
	private $btn;

    protected function startup()
    {
        parent::startup();
		if (!$this->getUser()->isLoggedIn()
			&& $this->getAction() != "login"
			&& $this->getAction() != "logout"
			&& $this->getAction() != "forgottenPassword"
			&& $this->getAction() != "changePassword"
			&& $this->getAction() != "signup") {
			$this->redirect(':login');
		}
        
    }

	public function __construct(
		private Nette\Database\Connection $database,
		private Accounts $accounts,
		private Email $email,
	) {
	}

    public function renderDefault(): void
	{
		$this->redirect('App:');
	}

	public function renderLogin(): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect(':default');
		}
		$this->template->btn = $this->btn;
	}

	public function renderSignup(): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect(':default');
		}
	}

	public function renderForgottenPassword() {
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect(':default');
		}
	}

	public function renderChangePassword() {
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect(':default');
		}
		$token = $this->getHttpRequest()->getQuery("token");
		if (!$this->accounts->tokenIsValid($token)) {
			$this->flashMessage('Request is not valid', 'warning');
			$this->redirect(':default');
		}
		if ($this->accounts->tokenExpired($token)) {
			$this->flashMessage('Token has already expired. Repeat the password reset, please.', 'warning');
			$this->redirect(':default');
		}
	}

	public function actionLogout()
	{
		$this->getUser()->logout(true);
		$this->flashMessage('Logout was successful.', 'warning');
		$this->redirect(':login');
	}


    protected function createComponentLoginForm(): Form
	{
		$form = new Form;
		$form->addText('username');
        $form->addPassword('password');
		$form->addSubmit('submit');
		$form->onSuccess[] = [$this, 'loginFormProcess'];
		return $form;
	}

	public function loginFormProcess(Form $form, $data): void
	{
		$values = $form->getValues();
		
        try {

			if (isset($this->getUser()->getIdentity()->restricted)) {
				try {
					if ($this->getUser()->getIdentity()->restricted > date('Y-m-d H:i:s')) {
						throw new \Exception("Too many error attempts, try again later.", 1);
					}
				} catch (\Exception $e) {
					$this->flashMessage($e->getMessage(), 'error');
					$this->redirect('this');
				}
			}

            $this->getUser()->login($values->username, $values->password);
			$this->getUser()->getIdentity()->restricted = false;

			$this->redirect(':');

        } catch (Nette\Security\AuthenticationException $e) {
            $this->flashMessage('Login credentials are incorrect.', 'error');
            $this->redrawControl('flash');
        }
	}	

	protected function createComponentSignupForm(): Form
	{
		$form = new Form;
		$form->addText('name');
		$form->addText('surname');
		$form->addEmail('username');
        $form->addPassword('password');
		$form->addPassword('password2');
		$form->addSubmit('submit');
		$form->onSuccess[] = [$this, 'signupFormProcess'];
		return $form;
	}

	public function signupFormProcess(Form $form, $data): void
	{
		$values = $form->getValues();
		
        $this->flashMessage("Sing up process is currently unavailable, try again later, please.", "warning");
		$this->redrawControl("flash");

	}

	protected function createComponentResetPasswordForm(): Form
	{
		$form = new Form;
		$form->addText('username');
		$form->addSubmit('submit');
		$form->onSuccess[] = [$this, 'resetPasswordFormProcess'];
		return $form;
	}

	public function resetPasswordFormProcess(Form $form, $data): void
	{
		$values = $form->getValues();
		$err = false;
		
		try {
			if (!$this->accounts->emailExists($values->username)) {
				throw new \Exception("Inserted email address do not exists.", 1);	
			}

			$token = bin2hex(random_bytes(32)).'-'.bin2hex($values->username).'-'.bin2hex(random_bytes(32));
			$expiration = date("Y-m-d H:i:s", strtotime("+1 hour"));

			$accountId = $this->accounts->getAccountIdViaEmail($values->username);
			$this->accounts->updateResetToken($token, $accountId);
			$this->accounts->updateResetTokenExpiration($expiration, $accountId);
			$this->email->sendResetPasswordEmail($values->username,$token,$expiration);

		} catch (\Exception $e) {
			$this->flashMessage($e->getMessage(), "error");
			$this->redrawControl('flash');
			$err = true;
		}

		if (!$err) {
			$this->flashMessage('Link for password recovery has been sent to your registered email account. Check your inbox, can take a minute or two.', 'success');
			$this->redirect(":login");
		}

	}

	protected function createComponentChangePasswordForm(): Form
	{
		$token = $this->getHttpRequest()->getQuery("token");
		if (!$token) {
			$token = $this->getHttpRequest()->getPost("token");
		}

		$form = new Form;
		$form->addHidden('token')->setValue($token);
		$form->addPassword('password')->setMaxLength(255);
		$form->addPassword('password2')->setMaxLength(255);
		$form->addSubmit('submit');
		$form->onSuccess[] = [$this, 'changePasswordFormProcess'];
		return $form;
	}

	public function changePasswordFormProcess(Form $form, $data): void
	{
		$values = $form->getValues();
		$err = false;
		
		try {
			if (!$this->accounts->tokenIsValid($values->token) || !$values->token) {
				throw new \Exception("Request is not valid!", 1);			
			}
			if ($this->accounts->tokenExpired($values->token)) {
				throw new \Exception("Token has already expired. Repeat the password reset, please.", 1);	
			}
			if (strlen($values->password) < 8) {
				throw new \Exception("Password must contain atleast 8 characters!", 1);
			}
			if ($values->password !== $values->password2) {
				throw new \Exception("Passwords do not match!", 1);
			}

			$accountId = $this->accounts->getAccountIdViaToken($values->token);
			if (!$accountId) {
				throw new \Exception("Something is wrong! Can't find ID of the user!", 1);
			}

			$this->accounts->updatePassword($values->password, $accountId);
			$this->accounts->purgeResetToken($accountId);
			
		} catch (\Exception $e) {
			$this->flashMessage($e->getMessage(), "error");
			$this->redirect("this", ['token' => $values->token]);
			$err = true;
		}

		if (!$err) {
			$this->flashMessage('Password has been successfully recovered. Log in to your account, please.', 'success');
			$this->redirect(":login");
		}

	}


}
