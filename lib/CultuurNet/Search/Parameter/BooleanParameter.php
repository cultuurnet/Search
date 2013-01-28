<?php

namespace CultuurNet\Search\Parameter;

class BooleanParameter extends AbstractBooleanParameter
{
    public function __construct($key, $value) {
        $this->key = $key;

        // @todo check if value is a real boolean, otherwise throw an exception
        $this->value = $value;
    }
}
