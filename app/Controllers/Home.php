<?php

namespace App\Controllers;

class Home extends BaseController {

    public function index(): string {
        return view('welcome_message');
    }

    public function email() {
        $email = \Config\Services::email();

        $email->setFrom('your@example.com', "Your Name");
        $email->setTo('eef25e43be@emaily.pro');
//        $email->setCC('another@another-example.com');
//        $email->setBCC('them@their-example.com');

        $email->setSubject('Email Test');
        $email->setMessage('Teating the email class.');
        
        if($email->send()){
            echo 'Email enviado';
        } else {
            echo $email->printDebugger();
        }
    }
}
