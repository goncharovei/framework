<?php

namespace Foundation\Kernels\Http;

class Response extends \GuzzleHttp\Psr7\Response
{
    public function setContent(string $content): static
    {
        $this->getBody()->write($content);

        return $this;
    }

    public function setHeaders(array $headers): static
    {
        foreach ($headers as $name=>$value)
        {
            $this->withHeader($name, $value);
        }

        return $this;
    }
}