<?php

namespace CultuurNet\Search\Parameter;

abstract class AbstractBooleanParameter extends AbstractParameter {

  public function getValue() {
    return $this->value ? 'true' : 'false';
  }
}
