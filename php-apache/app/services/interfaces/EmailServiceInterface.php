<?php

namespace services\interfaces;

interface EmailServiceInterface
{
    public function send(string $to, string $subject, string $template, array $data = []): bool;
    public function sendVerification(string $to, string $username, string $token): bool;
    public function sendPasswordReset();
    public function sendCommentNotification();
}
