<?php

namespace CultuurNet\Search\Parameter;

class DateTypeQuery extends AbstractParameter {
  public function __construct($value) {
    $this->value = $value;
    $this->key = 'datetype';
  }
}
