<?php

namespace Foundation\Mail\PhpMailer;

use PHPMailer\PHPMailer\PHPMailer;

interface PhpMailerTransport
{
    public function init(): static;
}