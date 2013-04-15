<?php

namespace CultuurNet\Search\Parameter;

class Parameter extends AbstractParameter {

  public function __construct($key, $value) {
    $this->key = $key;
    $this->value = $value;
  }
}
