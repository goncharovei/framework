<?php

namespace Foundation\Mail\PhpMailer\Transport;

use Foundation\Mail\PhpMailer\PhpMailerFactory;

class PhpMailerQmail extends PhpMailerFactory
{

    public function init(): static
    {
        $this->mailer->isQmail();

        return $this;
    }
}