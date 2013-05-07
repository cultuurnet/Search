<?php

namespace CultuurNet\Search\Parameter;

class Title extends AbstractParameter {

  public function __construct($value) {
    $this->key = 'title';

    $this->value = $value;
  }

}
