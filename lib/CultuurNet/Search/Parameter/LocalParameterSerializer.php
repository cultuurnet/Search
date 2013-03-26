<?php

namespace CultuurNet\Search\Parameter;

class LocalParameterSerializer {
  public function serialize($localParams) {
    $keyValuePairs = array();

    foreach ($localParams as $key => $value) {
      // @todo check if backslashes really need to be escaped here
      $value = str_replace('\\', '\\\\', $value);

      if (FALSE !== strpos($value, ' ')) {
        $value = '"' . str_replace('"', '\"', $value) . '"';
      }
      // @todo do we need more escaping, chars like } maybe?
      $keyValuePairs[] = "{$key}={$value}";
    }

    $paramString = implode(' ', $keyValuePairs);

    return "{!{$paramString}}";
  }
}
