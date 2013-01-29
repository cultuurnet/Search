<?php

namespace CultuurNet\Search\Parameter;

abstract class AbstractParameter implements ParameterInterface {

    protected $key;

    protected $value;

    protected $localParams = array();

    public function getKey() {
        return $this->key;
    }

    public function getValue() {
        return $this->value;
    }

    public function getLocalParams() {
        return $this->localParams;
    }
}
