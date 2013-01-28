<?php

namespace CultuurNet\Search\Parameter;

class Query extends AbstractParameter {

    /**
     * @param object|string $value
     */
    public function __construct($value)
    {
        // @todo check type of value, should be string
        $this->value = $value;

        $this->key = 'q';
    }
}
