<?php

namespace CultuurNet\Search\Parameter;

class Type extends AbstractParameter {

    public function __construct($value) {
        $this->key = 'type';

        $this->value = $value;
    }

}
