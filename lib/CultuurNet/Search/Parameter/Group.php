<?php

namespace CultuurNet\Search\Parameter;

class Group extends AbstractBooleanParameter {
  public function __construct($value = TRUE) {
    $this->key = 'group';

    // @todo check if value is a real boolean, otherwise throw an exception
    $this->value = $value;
  }
}
