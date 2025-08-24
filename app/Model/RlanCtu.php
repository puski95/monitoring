<?php

declare(strict_types=1);

namespace App\Model;

use Nette;

final class RlanCtu
{

    private $url = "https://rlantest.ctu.gov.cz/api/v1";
    private $email = "ssssss";
    private $password = "Paasdqweqwer";

    const EIRP_METHOD = array(
        "auto" => "auto",
        "manual" => "manual"
    );

    public function __construct(
        private Nette\Database\Explorer $database
        ) {
    }

    public function getAccessToken() {
        $token = $this->database->fetchField('SELECT access_token FROM rlan_token WHERE id = 1');
        return $token;
    }

    public function getUserStatus() {
        $uri = "/user/status";
        $url = $this->url . $uri;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'access-token: '.$this->getAccessToken()
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response);
    }

    public function getStations() {
        $uri = "/station";
        $url = $this->url . $uri;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'access-token: '.$this->getAccessToken()
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response);
    }

    public function getStation($id) {
        $uri = "/station/" . $id;
        $url = $this->url . $uri;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'access-token: '.$this->getAccessToken()
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response);
    }

    public function updateStation($id, $data) {
        $uri = "/station/" . $id;
        $url = $this->url . $uri;

        $data = json_encode($data);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'access-token: '.$this->getAccessToken()
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response);
    }

    public function relengthStation($id) {
        $uri = "/station/" . $id . "/relength";
        $url = $this->url . $uri;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'access-token: '.$this->getAccessToken()
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response);
    }

    public function unpublishStation($id) {
        $uri = "/station/" . $id . "/unpublish";
        $url = $this->url . $uri;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'access-token: '.$this->getAccessToken()
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response);
    }

    public function publishStation($id) {
        $uri = "/station/" . $id . "/publish";
        $url = $this->url . $uri;

        $data = [
            "solveConflictByDeclaration" => true
        ];

        $data = json_encode($data);



        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'access-token: '.$this->getAccessToken()
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response);
    }

    public function logout() {
        $uri = "/user/logout";
        $url = $this->url . $uri;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'accept: application/json'
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response);

    }

    public function login() {
        $uri = "/user/login";
        $url = $this->url . $uri;
        $data = array(
            'email' => $this->email,
            'password' => $this->password
        );

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer YOUR_ACCESS_TOKEN'
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response);
    }


}	