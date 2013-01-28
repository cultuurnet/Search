<?php

namespace CultuurNet\Search\Parameter;

abstract class AbstractParameter implements ParameterInterface {

    protected $key;

    protected $value;

    public function getKey() {
        return $this->key;
    }

    public function getValue() {
        return $this->value;
    }
}
