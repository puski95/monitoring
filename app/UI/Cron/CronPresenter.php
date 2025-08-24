<?php

declare(strict_types=1);

namespace App\UI\Cron;

use Nette;
use Nette\Application\UI\Form;

final class CronPresenter extends Nette\Application\UI\Presenter
{

	private $id;
	private $api_keys = [
		"as86#-42QWs#Jh"
	];
	private $services = [
		"cron_job"
	];
	private $ips = [
		"89.239.5.87",
		"192.168.50.1"
	];

    protected function startup()
    {
        parent::startup();

		if (!$this->accessGranted()) {
			$this->error('Access Restricted', 403);
		}
       
    }

	public function __construct(
		private Nette\Database\Connection $database,
	) {

	}

    public function renderDefault(): void
	{
		$this->error('Not Available', 404);
	}


	/**
	 * Overeni opravneni ke vstupu
	 */
	protected function accessGranted() {
		$api_key = $this->getHttpRequest()->getPost('api_key');
		$service = $this->getHttpRequest()->getPost('service');
		$ip = $_SERVER['REMOTE_ADDR'];

		try {

			if (!in_array($api_key, $this->api_keys)) {
				throw new \Exception("Neplatný API key", 1);
			}
			if (!in_array($service, $this->services)) {
				throw new \Exception("Neplatná služba", 1);
			}
			if (!in_array($ip, $this->ips)) {
				throw new \Exception("Neplatná IP adresa", 1);
			}

			return true;
		} catch (\Exception $e) {
			return false;
		}
	}


}
