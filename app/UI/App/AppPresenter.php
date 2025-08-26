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


		$this->template->view = $view;
		$this->template->id = $id;
		$count = 0;

		if ($this->isAjax()) {

			$this->redrawControl('content');

            $this->template->js = <<<HTML
			<script n:syntax=off>
			(() => {
				const ajaxLinks = document.querySelectorAll('a.ajax');
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
