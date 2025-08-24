<?php

declare(strict_types=1);

namespace App\Model;

use Nette;

final class Config
{

    public function __construct(
        private Nette\Database\Explorer $database
        ) {
    }

    public function getConfig() {
        return $this->database->fetch('SELECT * FROM config WHERE id = 1');
    }

    public function updateSmtpServer($value) {
        $this->database->query("UPDATE config SET smtp_server = ? WHERE id = 1", $value);
    }
    public function updateSmtpPort($value) {
        $this->database->query("UPDATE config SET smtp_port = ? WHERE id = 1", $value);
    }
    public function updateSmtpUsername($value) {
        $this->database->query("UPDATE config SET smtp_username = ? WHERE id = 1", $value);
    }
    public function updateSmtpPassword($value) {
        $this->database->query("UPDATE config SET smtp_password = ? WHERE id = 1", $value);
    }
    public function updateSmtpEncryption($value) {
        $this->database->query("UPDATE config SET smtp_encryption = ? WHERE id = 1", $value);
    }
    public function updateSmtpEmail($value) {
        $this->database->query("UPDATE config SET smtp_email = ? WHERE id = 1", $value);
    }

    public function maintenance() {
        if ($this->database->fetchField('SELECT maintenance FROM config WHERE id = 1')) {
            return true;
        } else {
            return false;
        }
    }

    public function toggleMaintenance() {
        if ($this->maintenance()) {
            $status = 0;
        } else {
            $status = 1;
        }
        $this->database->query('UPDATE config SET maintenance = ? WHERE id = 1', $status);
        return true;
    }

}	