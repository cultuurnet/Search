<?php

namespace CultuurNet\Search\Parameter;

class Start extends AbstractParameter
{
    public function __construct($value)
    {
        $this->key = 'start';

        // @todo check type of value, should be numeric
        if (!ctype_digit($value)) {

        }

        $this->value = intval($value);
    }
}
