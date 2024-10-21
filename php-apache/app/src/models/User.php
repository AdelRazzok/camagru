<?php

class User
{
    private int $id;
    private string $email;
    private string $username;
    private string $password;
    private bool $email_verified;
    private bool $email_notif_on_comment;
    private DateTime $created_at;
    private DateTime $updated_at;
}
