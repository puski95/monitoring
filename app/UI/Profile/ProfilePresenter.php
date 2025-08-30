<?php

declare(strict_types=1);

namespace App\UI\Profile;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Config;

final class ProfilePresenter extends Nette\Application\UI\Presenter
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
