<?php

namespace CultuurNet\Search\Parameter;

class Query extends AbstractParameter {

  /**
   * @param object|string $value
   */
  public function __construct($value) {

    // Search queries should not start with a colon.
    if (strpos(':', $value) == 0) {
      $value = '\:' . substr($value, 1);
    }

    // @todo check type of value, should be string
    $this->value = $value;
    $this->key = 'q';
  }

  /**
   * @param string $key
   * @param string $value
   * @return self
   */
  public function setLocalParam($key, $value) {
    // @todo type checking
    // @todo proper escaping?
    $this->localParams[$key] = $value;

    return $this;
  }
}
