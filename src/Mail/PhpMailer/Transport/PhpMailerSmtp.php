<?php

namespace Foundation\Mail\PhpMailer\Transport;

use Foundation\Mail\PhpMailer\PhpMailerFactory;

class PhpMailerSmtp extends PhpMailerFactory
{

    public function init(): static
    {
        $config = $this->config['mailers']['smtp'];

        $this->mailer->isSMTP();
        $this->mailer->SMTPSecure = $config['encryption'];
        $this->mailer->Host = $config['host'];
        $this->mailer->Port = $config['port'];
        $this->mailer->SMTPDebug = $config['debug'];
        $this->mailer->Username = $config['username'];
        $this->mailer->Password = $config['password'];
        $this->mailer->SMTPAuth = $this->isAuth();

        return $this;
    }

    private function isAuth(): bool
    {
        return !empty($config['username']) && !empty($config['password']);
    }
}