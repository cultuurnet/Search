<?php

namespace CultuurNet\Search\Parameter;

class Group extends AbstractParameter {

  public function __construct($value = TRUE) {
    $this->key = 'group';
    $this->value = $value;
  }

  public function getValue() {
    if ($this->value === TRUE) {
      return 'true';
    }
    elseif ($this->value === FALSE) {
      return 'false';
    }
    return $this->value;
  }

}
