<?php

declare(strict_types=1);

namespace App\UI\App;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Config;

final class AppPresenter extends Nette\Application\UI\Presenter
{

	private $id;

    protected function startup()
    {
        parent::startup();
        
		$this->protected();
    }

	public function __construct(
		private Nette\Database\Connection $database,
        private Config $config
	) {

	}

    public function renderDefault($id): void
	{

		$this->template->id = $id;

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
