<?php

declare(strict_types=1);

namespace App\UI\App;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Config;

final class AppPresenter extends Nette\Application\UI\Presenter
{

	private $id;
	private int $ajaxRedrawCount = 0;

    protected function startup()
    {
        parent::startup();
        
    }

	public function __construct(
		private Nette\Database\Connection $database,
        private Config $config
	) {

	}

    public function renderDefault($view, $id): void
	{
		if (!$this->getUser()->isLoggedIn() && $view !== "login") {
			$this->redirect("this", ["view" => "login"]);
		}
		if ($this->getUser()->isLoggedIn() && $view === "login") {
			$this->redirect("this", ["view" => null]);
		}
		if ($view === "logout") {
			$this->getUser()->logout(true);
			$this->redirect("this", ["view" => "login"]);
		}

		$this->template->view = $view;
		$this->template->id = $id;
		$count = 0;

		if ($this->isAjax() && !$this->getHttpRequest()->getQuery("do")) {
			$this->redrawControl('content');
            $this->template->js = $this->javascript();
    	}


	}

	protected function javascript() {
		return <<<HTML
			<script n:syntax=off>
			(() => {
				const ajaxLinks = document.querySelectorAll('a.ajaxlink');
				ajaxLinks.forEach(link => {
					link.addEventListener('click', function(e) {
						e.preventDefault();
						history.pushState(null, '', link.href);
					});
				});
			})();
			</script>
			HTML;
	}

	/**
	 * FORMS
	 */

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
            $this->flashMessage('Username or Password do not match.', 'error');
            $this->redrawControl('flash');
        }
	}	

	protected function createComponentContactForm(): Form
	{
		$form = new Form;

		$form->addSubmit('submit');
		$form->onSuccess[] = [$this, 'contactFormProcess'];
		return $form;
	}

	public function contactFormProcess(Form $form, $data): void
	{

		$values = $form->getValues();
        

	}
    

}
