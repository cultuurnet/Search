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
    return $this->value;
  }

  public function getLocalParams() {
    return $this->localParams;
  }

  /**
   * Escape special characters for a correct solr search query.
   * @param string $string
   *   String to escape
   * @return string
   */
  public static function escape($string) {
    $match = array('\\', '+', '-', '&', '|', '!', '(', ')', '{', '}', '[', ']', '^', '~', '*', '?', ':', '"', ';', ' ');
    $replace = array('\\\\', '\\+', '\\-', '\\&', '\\|', '\\!', '\\(', '\\)', '\\{', '\\}', '\\[', '\\]', '\\^', '\\~', '\\*', '\\?', '\\:', '\\"', '\\;', '\\ ');
    return str_replace($match, $replace, $string);
}

}
