<?php

namespace CultuurNet\Search\Parameter;

class FacetField extends AbstractParameter {

  /**
   * @param $fieldName
   */
  public function __construct($fieldName) {
    $this->key = 'facetField';

    // @todo check type of $fieldName, should be string
    $this->value = $fieldName;
  }

  /**
   * @param array $tags
   */
  public function setExcludes($tags) {
    $this->localParams['ex'] = implode(',', $tags);
  }

  public function setFieldKey($key) {
    $this->localParams['key'] = $key;

    return $this;
  }

  public function setFacetPrefix($prefix) {
    $this->localParams['facet.prefix'] = $prefix;

    return $this;
  }
}
