<?php

namespace Foundation\Mail\PhpMailer\Transport;

use Foundation\Mail\PhpMailer\PhpMailerFactory;

class PhpMailerMail extends PhpMailerFactory
{

    public function init(): static
    {
        $this->mailer->isMail();

        return $this;
    }

}