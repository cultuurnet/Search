<?php

namespace CultuurNet\Search\Parameter;

class Group extends AbstractParameter {
  public function __construct($value = TRUE) {
    $this->key = 'group';
    $this->value = $value;
  }
}
