<?php

declare(strict_types=1);

namespace App\Model;

use Nette;

class Email {

    private $config;

    public function __construct(
        private Nette\Database\Explorer $database
        ) {
        
    }

    public function sendTestEmail($receiver) {

        $config = new \App\Model\Config($this->database);
        $config = $config->getConfig();
        
        $mail = new Nette\Mail\Message;
        $mail->setFrom($config->smtp_email)
            ->addTo($receiver)
            ->setSubject('Testovací e-mail z aplikace')
            ->setHtmlBody($this->templateTestEmail());

        if (!empty($config->smtp_server)) {
            $this->sendViaSmtp($mail);
        } else {
            throw new \Exception("Služba e-mail je nedostupná.", 1);    
        }
    }

    public function sendResetPasswordEmail($receiver, $token, $expiration) {

        $config = new \App\Model\Config($this->database);
        $config = $config->getConfig();
        
        $mail = new Nette\Mail\Message;
        $mail->setFrom($config->smtp_email)
            ->addTo($receiver)
            ->setSubject('Žádost o obnovu hesla - '.$_SERVER['SERVER_NAME'].'')
            ->setHtmlBody($this->templateResetPasswordEmail($token, $expiration));

        if (!empty($config->smtp_server)) {
            $this->sendViaSmtp($mail);
        } else {
            throw new \Exception("Služba e-mail je nedostupná.", 1);    
        }
    }

    protected function sendViaMailer($mail) {
        $mailer = new Nette\Mail\SendmailMailer;
        $mailer->send($mail);
    }

    protected function sendViaSmtp($mail) {
        $config = new \App\Model\Config($this->database);

        $config = $config->getConfig();

        $mailer = new Nette\Mail\SmtpMailer(
            host: $config->smtp_server,
            username: $config->smtp_username,
            password: $config->smtp_password,
            encryption: $config->smtp_encryption,
            port: $config->smtp_port
        );
        $mailer->send($mail);
    }

    protected function templateTestEmail() {
        $email = "
            <h2>Testovací zpráva konfigurace e-mailové konfigurace</h2>

            <p>Pokud tato zpráva dorazila, je nastavení plně funkční.</p>

        ";
        return $email;
    }

    protected function templateResetPasswordEmail($token,$expiration) {
        $url = "https://".$_SERVER['SERVER_NAME']."/auth/change-password?token=".$token;
        $email = "
        
        <h2>Žádost o obnovu hesla</h2>

        <p>Pro obnovu hesla k Vašemu účtu prosím použijte následující <a href=".$url.">odkaz</a>.</p>
        <br>
        <p>Odkaz je platný 1 hodinu od vytvoření požadavku. Po uplynutí této lhůty bude potřeba proces zopakovat.</p>
        <br>
        <br>
        <small>Pokud jste o změnu hesla nežádali, důrazně doporučeme o kontrolu Vašeho účtu a případně zvýšení jeho zabezpečení.</small>
        <br>
        <br>
        <p>-----------</p>
        <br>
        <small><i>Tato zpráva byla vygenerována systémem Simple CMS. Na tuto zprávu neodpovídejte...</i></small>
        
        ";

        return $email;
    }


}