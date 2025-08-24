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

        if ($this->config->maintenance() && $this->action != "maintenance") {
            if (isset($this->getUser()->getIdentity()->admin) && $this->getUser()->getIdentity()->admin) {
                # admin access
            } else {
                $this->redirect(':maintenance');
            }
        }

        $this->template->config = $this->config->getConfig();
        
    }

	public function __construct(
		private Nette\Database\Connection $database,
        private Config $config
	) {

	}

    public function renderDefault(): void
	{
		
	}

    public function renderMaintenance(): void
    {
        if (!$this->config->maintenance()) {
            $this->redirect(':default');
        }
        if (isset($this->getUser()->getIdentity()->admin) && $this->getUser()->getIdentity()->admin) {
            $this->redirect(':default');
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
