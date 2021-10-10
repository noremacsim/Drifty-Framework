<?php
/*
 * Drifty FrameWork by noremacsim(Cameron Sim)
 *
 * This File has been created by noremacsim(Cameron Sim) under the Drifty FrameWork
 * And will follow all the Drifty FrameWork Licence Terms which can be found under Licence
 *
 * @author     Cameron Sim <mrcameronsim@gmail.com>
 * @author     noremacsim <noremacsim@github>
 */

namespace Drifty\mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class mailer {

    private $mailer;
    private $host;
    private $port;
    private $username;
    private $password;
    private $headers = '';
    public  $message = '';

    public function __contruct()
    {
        if ($GLOBALS['email'])
        {
            $this->host = $GLOBALS['email']['host'];
            $this->port = $GLOBALS['email']['port'];
            $this->username = $GLOBALS['email']['username'];
            $this->password = $GLOBALS['email']['password'];
        }
    }


    public function connect(): void
    {
        $this->mailer = new PHPMailer();
        $this->mailer->isSMTP();
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->Host = $this->host;
        $this->mailer->SMTPDebug  = 0;
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Port       = $this->port;
        $this->mailer->Username   = $this->username;
        $this->mailer->Password   = $this->password;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->isHTML(true);
    }

    /**
     * @return bool
     */
    public function debug(): bool
    {
        $this->mailer->SMTPDebug  = 1;
        return true;
    }

    /**
     * @param $email
     * @param $name
     * @return bool
     */
    public function from($email, $name): bool
    {
        $this->mailer->setFrom($email, $name);
        return true;
    }

    /**
     * @param $email
     * @param $name
     * @return bool
     */
    public function to($email, $name): bool
    {
        $this->mailer->addAddress($email, $name);
        return true;
    }

    /**
     * @param $email
     * @param $name
     */
    public function cc($email, $name): void
    {
        $this->headers .= sprintf('Cc: %s <%s>', $name, $email) . "\r\n";
    }

    /**
     * @param $email
     * @param $name
     */
    public function bcc($email, $name): void
    {
        $this->headers .= sprintf('Bcc: %s <%s>', $name, $email) . "\r\n";
    }

    /**
     * @param $file
     */
    public function attachment($file): void
    {
        $this->mailer->addAttachment($file);
    }

    /**
     * @param $email_body
     */
    public function body($email_body): void
    {
        $this->mailer->Body    	= $email_body;
    }

    /**
     * @param $subject
     */
    public function subject($subject): void
    {
        $this->mailer->Subject 	= $subject;
    }

    /**
     * @return mixed
     */
    public function send()
    {
        $email = $this->mailer->send();
        return $email;
    }

}