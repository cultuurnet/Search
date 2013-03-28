<?php

namespace CultuurNet\Search\Component\Facet;

class FacetResult {

  /**
   * @var FacetResultItem[]
   */
  protected $items = array();

  public function addItem(FacetResultItem $item) {
    $this->items[] = $item;
  }

  public function getItems() {
    return $this->items;
  }

}
