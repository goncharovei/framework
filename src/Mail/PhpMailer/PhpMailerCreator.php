<?php

namespace Foundation\Mail\PhpMailer;

use Foundation\Mail\PhpMailer\Transport\PhpMailerMail;
use Foundation\Mail\PhpMailer\Transport\PhpMailerQmail;
use Foundation\Mail\PhpMailer\Transport\PhpMailerSendmail;
use Foundation\Mail\PhpMailer\Transport\PhpMailerSmtp;
use PHPMailer\PHPMailer\PHPMailer;

final class PhpMailerCreator
{
    public static function factory(PHPMailer $mailer, array $config): PhpMailerFactory
    {
        $mailerTransport = match ($config['default']) {
            'smtp' => new PhpMailerSmtp($mailer, $config),
            'sendmail' => new PhpMailerSendmail($mailer, $config),
            'mail' => new PhpMailerMail($mailer, $config),
            'qmail' => new PhpMailerQmail($mailer, $config),
            default => throw new \InvalidArgumentException('Unknown PhpMailer type specified'),
        };
        $mailerTransport->settingsGeneral()->init();

        return $mailerTransport;
    }
}