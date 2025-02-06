<?php

namespace Foundation\Mail\PhpMailer;

use PHPMailer\PHPMailer\PHPMailer;

abstract class PhpMailerFactory implements PhpMailerTransport
{
    public function __construct(
        protected PHPMailer $mailer,
        protected readonly array $config
    )
    {

    }

    public function getInstance(): PHPMailer
    {
        return $this->mailer;
    }

    public function settingsGeneral(): static
    {
        $this->mailer->CharSet = $this->config['charset'];
        $this->mailer->setFrom(
            address: $this->config['from']['address'],
            name: $this->config['from']['name']
        );

        return $this;
    }

}