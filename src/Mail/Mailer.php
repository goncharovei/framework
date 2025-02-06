<?php

namespace Foundation\Mail;

use Foundation\Mail\PhpMailer\PhpMailerCreator;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    public function __invoke(): PHPMailer
    {
        $config = config('mail', []);
        $mailer = new PHPMailer(
            exceptions: $config['is_show_external_exceptions']
        );

        return PhpMailerCreator::factory($mailer, $config)
            ->getInstance();
    }
}