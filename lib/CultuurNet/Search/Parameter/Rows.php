<?php

namespace CultuurNet\Search\Parameter;

class Rows extends AbstractParameter
{
    public function __construct($value)
    {
        $this->key = 'rows';

        // @todo check type of value, should be numeric
        if (!ctype_digit($value)) {

        }

        $this->value = intval($value);
    }
}
