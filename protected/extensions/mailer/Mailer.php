<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Jenssegers\Blade\Blade;


class Mailer extends CComponent
{
	public $mailer;
	public $auth;
	public $host;
	public $port;
	public $username;
	public $password;
	public $senderEmail;
	public $senderName;
	public $render;

	public function init()
	{
		$this->mailer = new PHPMailer(true);
		$this->mailer->IsSMTP();
		$this->mailer->Host = $this->host;
		$this->mailer->Port = $this->port;
		$this->mailer->SMTPAuth = $this->auth;
		$this->mailer->Username = $this->username;
		$this->mailer->Password = $this->password;
		$this->mailer->SetFrom($this->senderEmail, $this->senderName);
		$this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$this->mailer->isHTML(true);
		$this->render = new Blade(dirname(__DIR__, 2) . '/views/email', dirname(__DIR__, 2) . '/views/cache');
	}

	public function to($to)
	{
		$this->mailer->addAddress($to);
	}

	public function subject($subject)
	{
		$this->mailer->Subject = $subject;
	}

	public function body($body, $payload)
	{
		$this->mailer->isHTML(true);
		$this->mailer->Body = $this->render->make($body, $payload)->render();
	}

	public function compose($payload)
	{
		$this->to($payload['to']);
		$this->subject($payload['subject']);
		$this->body($payload['body'], $payload['items']);
		$this->send();
	}

	public function send()
	{
		try {
			$this->mailer->send();
		} catch (Exception $e) {
			var_dump($e);
			exit;
		}
	}
}
