<?php

declare(strict_types=1);

namespace App\Model;

use Nette;

final class Accounts
{

    public function __construct(
        private Nette\Database\Explorer $database,
        ) {
    }

    public function createAccount($name,$surname,$email,$password,$mobile,$admin) {
        try {
            if ($this->emailExists($email)) {
                throw new \Exception("Tento E-mail už někdo používá!", 1);             
            }
            $password = password_hash($password,PASSWORD_DEFAULT);

            $this->database->query("INSERT INTO accounts(name,surname,username,password,mobile,admin) VALUES(?,?,?,?,?,?)",$name,$surname,$email,$password,$mobile,$admin);
            return $this->database->getInsertId();

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function deleteAccount($id) {
        $this->database->query("DELETE FROM accounts WHERE id = ?", $id);
    }

    public function emailExists($value) {
        $email = $this->database->fetchField('SELECT username FROM accounts WHERE username = ?', $value);
        return $email;
    }

    public function getUsername($accountId) {
        return $this->database->fetchField('SELECT username FROM accounts WHERE id = ?', $accountId);
    }

    public function getAvatar($accountId) {
        return $this->database->fetchField('SELECT avatar FROM accounts WHERE id = ?', $accountId);
    }

    public function getAccountIdViaToken($token) {
        return $this->database->fetchField('SELECT id FROM accounts WHERE reset_token = ?', $token);
    }
    public function getAccountIdViaEmail($email) {
        return $this->database->fetchField('SELECT id FROM accounts WHERE username = ?', $email);
    }

    public function updateName($value, $accountId) {
        $this->database->query("UPDATE accounts SET name = ? WHERE id = ?", $value, $accountId);
    }

    public function updateSurname($value, $accountId) {
        $this->database->query("UPDATE accounts SET surname = ? WHERE id = ?", $value, $accountId);
    }

    public function updateMobile($value, $accountId) {
        $this->database->query("UPDATE accounts SET mobile = ? WHERE id = ?", $value, $accountId);
    }

    public function updateUsername($value, $accountId) {
        $this->database->query("UPDATE accounts SET username = ? WHERE id = ?", $value, $accountId);
    }

    public function updateAdmin($value, $accountId) {
        $this->database->query("UPDATE accounts SET admin = ? WHERE id = ?", $value, $accountId);
    }

    public function updateResetToken($value, $accountId) {
        $this->database->query("UPDATE accounts SET reset_token = ? WHERE id = ?", $value, $accountId);
    }

    public function updateResetTokenExpiration($value, $accountId) {
        $this->database->query("UPDATE accounts SET reset_token_expiration = ? WHERE id = ?", $value, $accountId);
    }

    public function updateAvatar($value, $accountId) {
        $oldAvatar = $this->getAvatar($accountId);
        $directory = __DIR__."/../Media/Avatar/";    
        if ($oldAvatar != "avatar.png") { // default avatara nemazeme
            @unlink($directory.$oldAvatar);
        }
        $this->database->query("UPDATE accounts SET avatar = ? WHERE id = ?", $value, $accountId);
    }

    public function updatePassword($password, $accountId) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->database->query("UPDATE accounts SET password = ? WHERE id = ?", $hash, $accountId);
    }

    public function purgeResetToken($accountId) {
        $this->database->query("UPDATE accounts SET reset_token = null, reset_token_expiration = null WHERE id = ?", $accountId);
    }

    public function tokenIsValid($token) {
        $result = $this->database->fetchField('SELECT reset_token FROM accounts WHERE reset_token = ?', $token);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    public function tokenExpired($token) {
        $expirationDate = $this->database->fetchField('SELECT reset_token_expiration FROM accounts WHERE reset_token = ?', $token);
        if (date("Y-m-d H:i:s") > $expirationDate) {
            return true;
        } else {
            return false;
        }
    }




}	