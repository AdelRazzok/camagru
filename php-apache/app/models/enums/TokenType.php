<?php

namespace models\enums;

enum TokenType: string
{
    case EmailVerification = 'email_verification';
    case PasswordReset = 'password_reset';
}
