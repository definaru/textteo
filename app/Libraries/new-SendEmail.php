<?php

namespace App\Libraries;

class SendEmail
{
    private $mail;
    private $mail_type;
    private $title;
    private $sendgrid;
    private $sendFrom;

    public function __construct()
    {
        helper('file');
        $this->mail = !empty(settings("mail_option")) ? settings("mail_option") : 2;
        $this->mail_type = !empty(settings("email_option")) ? settings("email_option") : "smtp";
        $this->title = !empty(settings("website_name")) ? settings("website_name") : "Textteo";
        if ($this->mail == 1) {
            if ($this->mail_type == 'smtp') {
                $this->sendFrom = !empty(settings("email_address")) ? libsodiumDecrypt(settings("email_address")) : 'mail.textteo.com';
            } else {
                $this->sendgrid = !empty(settings("sendgrid_apikey")) ? libsodiumDecrypt(settings("sendgrid_apikey")) : "Bu95Q!FqV4jgzk3";
            }
        } else {
            return true;
        }
    }
    /**
     * SMTP Mail Send Code
     */
    private function smtpMailSet($toMail, $subject, $message)
    {
        $email = \Config\Services::email();
        $smtp_host = !empty(settings("smtp_host")) ? settings("smtp_host") : "mail.textteo.com";
        $smtp_port = !empty(settings("smtp_port")) ? settings("smtp_port") : "465";
        $smtp_user = !empty(settings("smtp_user")) ? libsodiumDecrypt(settings("smtp_user")) : "mail.textteo.com";
        $smtp_pass = !empty(settings("smtp_pass")) ? libsodiumDecrypt(settings("smtp_pass")) : "Bu95Q!FqV4jgzk3";
        // $config = array(
        //     'protocol'  => 'smtp',
        //     'smtp_host' => 'ssl://' . $smtp_host,
        //     'smtp_port' => $smtp_port,
        //     'smtp_user' => $smtp_user,
        //     'smtp_pass' => $smtp_pass,
        //     'mailtype'  => 'html',
        //     'charset'   => 'utf-8'
        //   );
        $config = array(
            'protocol'  => 'textteo',
            'smtp_host' => 'tls://' . 'mail.textteo.com',
            'smtp_port' => 587,
            'smtp_user' => 'mail@textteo.com',
            'smtp_pass' => 'Bu95Q!FqV4jgzk3',
            'mailtype'  => 'html',
            'charset'   => 'utf-8'
          );
        //echo '<pre>';print_r($config);exit;
        $email->initialize($config);

        $email->setFrom('mail@textteo.com', $subject);
        $email->setTo($toMail);
        $email->setSubject($subject);
        $email->setMessage($message);
        // $email->send();
        if ($email->send()) {
            echo 'Email sent successfully.';
        } else {
            echo 'Email could not be sent. Error: ' . $email->printDebugger();
        }
    }
    /**
     * SendGrid Mail Send Code / ToEmail / Subject And Message As a Parameter 
     */
    private function sendGridMailSet($toMail, $subject, $message)
    {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom(!empty(settings("email_address")) ? libsodiumDecrypt(settings("email_address")) : "mail.textteo.com", !empty(settings("email_tittle")) ? settings("email_tittle") : "Textteo");
        $email->setSubject($subject);
        $email->addTo($toMail);
        $email->addContent(
            "text/html",
            $message
        );
        $sendgrid = new \SendGrid($this->sendgrid);
        try {
            $response = $sendgrid->send($email);
        } catch (\Exception $e) {
        }
    }
    /**
     * Send Reset Password Link
     */
    public function sendResetPasswordEmail($data)
    {
        $message = '';

        $email_templates = email_template(3);
        $body = $email_templates['template_content'];
        $subject = $email_templates['template_subject'];

        $body = str_replace('{{site_url}}', base_url(), $body);
        $body = str_replace('{{site_logo}}', !empty(base_url() . settings("logo_front")) ? base_url() . settings("logo_front") : base_url() . "assets/img/logo.png", $body);
        // $body = str_replace('{{user_name}}', $data['first_name']?ucfirst(libsodiumDecrypt($data['first_name']??"") . ' ' . libsodiumDecrypt($data['last_name']??"")):"", $body);
        $firstName = isset($data['first_name']) ? libsodiumDecrypt($data['first_name']) : "";
        $lastName = isset($data['last_name']) ? libsodiumDecrypt($data['last_name']) : "";
        $userName = $firstName ? ucfirst($firstName . ' ' . $lastName) : "";
        $body = str_replace('{{user_name}}', $userName, $body);
        $body = str_replace('{{website_name}}', !empty(settings("website_name")) ? settings("website_name") : "Textteo", $body);
        $body = str_replace('{{email}}', libsodiumDecrypt($data['email']), $body);
        $body = str_replace('{{reset_url}}', base_url() . 'reset-password/' . $data['url'], $body);
        $body = str_replace('{{date}}', date('Y'), $body);

        $message = $body;

        if ($this->mail_type == 'smtp') {
            $this->smtpMailSet(libsodiumDecrypt($data['email']), $subject, $message);
        } else {
            $this->sendGridMailSet(libsodiumDecrypt($data['email']), $subject, $message);
        }
    }
    /**
     * Appointment Email Sent
     */
    public function sendAppoinmentEmail($data)
    {
        $message = '';

        $email_templates = email_template(2);
        $body = $email_templates['template_content'];
        $subject = $email_templates['template_subject'];

        $body = str_replace('{{site_url}}', base_url(), $body);
        $body = str_replace('{{site_logo}}', !empty(base_url() . settings("logo_front")) ? base_url() . settings("logo_front") : base_url() . "assets/img/logo.png", $body);
        $body = str_replace('{{doctor_name}}', ucfirst(libsodiumDecrypt($data['doctor_first_name'])), $body);
        $body = str_replace('{{website_name}}', !empty(settings("website_name")) ? settings("website_name") : "Textteo", $body);
        $body = str_replace('{{patient_name}}', (libsodiumDecrypt($data['patient_first_name']) . " " . libsodiumDecrypt($data['patient_last_name'])), $body);
        $body = str_replace('{{date}}', date('Y'), $body);

        $message = $body;

        if ($this->mail_type == 'smtp') {
            $this->smtpMailSet(libsodiumDecrypt($data['doctor_email']), $subject, $message);
        } else {
            $this->sendGridMailSet(libsodiumDecrypt($data['doctor_email']), $subject, $message);
        }
    }

    /**
     * Registeration Email Verification
     */
    public function send_email_verification($data)
    {
        $message = '';

        $email_templates = email_template(1);
        $body = $email_templates['template_content'];
        $subject = $email_templates['template_subject'];

        $subject = str_replace('{{website_name}}', !empty(settings("website_name")) ? settings("website_name") : "Textteo", $subject);
        $data['first_name'] = $data['last_name'] = " ";
        $body = str_replace('{{site_url}}', base_url(), $body);
        $body = str_replace('{{site_logo}}', !empty(base_url() . settings("logo_front")) ? base_url() . settings("logo_front") : base_url() . "assets/img/logo.png", $body);
        $body = str_replace('{{user_name}}', $data['first_name'] ? ucfirst(libsodiumDecrypt($data['first_name']) . ' ' . libsodiumDecrypt($data['last_name'])) : "", $body);
        $body = str_replace('{{website_name}}', !empty(settings("website_name")) ? settings("website_name") : "Textteo", $body);
        $body = str_replace('{{verify_url}}', base_url() . 'activate/' . md5($data['id']), $body);
        $body = str_replace('{{date}}', date('Y'), $body);

        $message = $body;

        if ($this->mail_type == 'smtp') {
            $this->smtpMailSet(libsodiumDecrypt($data['email']), $subject, $message);
        } else {
            $this->sendGridMailSet(libsodiumDecrypt($data['email']), $subject, $message);
        }
    }

    public function sendOrderStatusEmail($data) {
        $message = '';

        $email_templates = email_template(6);
        $body = $email_templates['template_content'];
        $subject = $email_templates['template_subject'];

        $body = str_replace('{{site_url}}', base_url(), $body);
        $body = str_replace('{{site_logo}}', !empty(base_url() . settings("logo_front")) ? base_url() . settings("logo_front") : base_url() . "assets/img/logo.png", $body);
        $body = str_replace('{{pharmacy_name}}', ucfirst(libsodiumDecrypt($data['pharmacy_first_name'])), $body);
        $body = str_replace('{{website_name}}', !empty(settings("website_name")) ? settings("website_name") : "Textteo", $body);
        $body = str_replace('{{patient_name}}', (libsodiumDecrypt($data['patient_name'])), $body);
        $body = str_replace('{{order_id}}', ($data['order_id'].' '.libsodiumDecrypt($data['product_name'])), $body);
        $body = str_replace('{{status}}', ($data['order_status']), $body);
        $body = str_replace('{{date}}', date('Y'), $body);

        $message = $body;

        if ($this->mail_type == 'smtp') {
            $this->smtpMailSet(libsodiumDecrypt($data['patient_email']), $subject, $message);
        } else {
            $this->sendGridMailSet(libsodiumDecrypt($data['patient_email']), $subject, $message);
        }
    }
}
