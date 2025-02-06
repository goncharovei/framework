<?php

namespace Foundation\Mail\PhpMailer\Transport;

use Foundation\Mail\PhpMailer\PhpMailerFactory;

class PhpMailerSendmail extends PhpMailerFactory
{

    public function init(): static
    {
        $this->mailer->isSendmail();

        return $this;
    }
}