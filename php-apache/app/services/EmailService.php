<?php

namespace services;

use services\interfaces\EmailServiceInterface;
use Exception;

class EmailService implements EmailServiceInterface
{
    private string $fromEmail;
    private string $fromName;
    private string $baseUrl;
    private string $templatesPath;

    public function __construct()
    {
        $this->fromEmail = getenv('MAIL_FROM_ADDRESS');
        $this->fromName = getenv('MAIL_FROM_NAME');
        $this->baseUrl = getenv('APP_URL');
        $this->templatesPath = dirname(__DIR__) . '/views/emails/';
    }

    public function send(string $to, string $subject, string $template, array $data = []): bool
    {
        $templateFile = $this->templatesPath . $template . '.php';
        if (!file_exists($templateFile)) {
            throw new Exception('Email template not found.');
        }

        $data['baseUrl'] = $this->baseUrl;
        extract($data);

        ob_start();
        include $templateFile;
        $body = ob_get_clean();

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . $this->fromName . ' <' . $this->fromEmail . '>',
            'X-Mailer: PHP/' . phpversion()
        ];

        $result = mail($to, $subject, $body, implode("\r\n", $headers));

        return $result;
    }

    public function sendVerification(string $to, string $username, string $token): bool
    {
        $subject = 'Verify your email';

        $data = [
            'username' => $username,
            'verificationLink' => $this->baseUrl . 'verify-account?token=' . $token
        ];
        return $this->send($to, $subject, 'verification', $data);
    }

    public function sendPasswordReset() {}
    public function sendCommentNotification() {}
}
