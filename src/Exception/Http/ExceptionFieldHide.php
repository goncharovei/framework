<?php

namespace Foundation\Exception\Http;

class ExceptionFieldHide extends ExceptionFieldPrototype
{

    public function getFields(): ExceptionFields
    {
        return new ExceptionFields(
            $this->getCode(),
            'Something went wrong'
        );
    }
}