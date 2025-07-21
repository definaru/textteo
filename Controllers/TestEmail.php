<?php

namespace App\Controllers;

class TestEmail extends BaseController
{
    public function index()
    {
        //$email = \Config\Services::email();
        $send_email = $this->request->getGet('email');
        $email = service('email');

        $email->setTo($send_email);
        $email->setSubject('Test letter');
        $email->setMessage('This is a test email from CodeIgniter 4.');

        if ($email->send()) {
            echo 'The letter has been sent!';
        } else {
            echo '<pre>';
            print_r($email->printDebugger(['headers', 'subject', 'body', 'smtp']));
            echo '</pre>';
        }
    }
}