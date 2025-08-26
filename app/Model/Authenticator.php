<?php

declare(strict_types=1);

use \Nette\Security\IIdentity;

class Authenticator implements \Nette\Security\Authenticator
{

    public function __construct(
        private \Nette\Database\Explorer $database,
        private \Nette\Security\Passwords $passwords,
    ) {}

    public function authenticate(string $username, string $password): IIdentity
    {
        $user = $this->database->table('accounts')->where('username', $username)->fetch();

        if ($user === null) {
            throw new \Nette\Security\AuthenticationException('User not found.');
        }

        if ($this->passwords->verify($password, $user->password) === false) {
            throw new \Nette\Security\AuthenticationException('Wrong password.');
        }

        return new \Nette\Security\SimpleIdentity($user->id, null, ['username' => $user->username,
                                                                    'admin' => $user->admin]);
    }

}