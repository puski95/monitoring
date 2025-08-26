<?php

declare(strict_types=1);

namespace App\UI\Default;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Config;

final class DefaultPresenter extends Nette\Application\UI\Presenter
{

	private $id;

    protected function startup()
    {
        parent::startup();
        
    }

	public function __construct(
		private Nette\Database\Connection $database,
        private Config $config
	) {

	}

    public function renderDefault(): void
	{
		
	}

	public function renderAdminer(): void {
		require_once("../adminer/adminer-5.3.0-cs.php");
		$this->terminate();
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
