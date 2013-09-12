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
    $match = array('\\', '+', '-', '&', '|', '!', '(', ')', '{', '}', '[', ']', '^', '~', '*', '?', ':', '"', ';', ' ');
    $replace = array('\\\\', '\\+', '\\-', '\\&', '\\|', '\\!', '\\(', '\\)', '\\{', '\\}', '\\[', '\\]', '\\^', '\\~', '\\*', '\\?', '\\:', '\\"', '\\;', '\\ ');
    return str_replace($match, $replace, $this->value);
  }

  public function getLocalParams() {
    return $this->localParams;
  }
}
