<?php

namespace CultuurNet\Search\Component\Facet;

class FacetResultItem {

  /**
   * Label from the item.
   * @var string
   */
  protected $label;

  /**
   * Value from the item.
   * @var string
   */
  protected $value;

  /**
   * Total results for this item.
   * @var int
   */
  protected $totalResults;

  /**
   * List of sub
   * @var array
   */
  protected $subItems = array();

  /**
   * Construct the result item.
   */
  public function __construct($label, $totalResults) {
    $this->label = $label;
    $this->value = $label;
    $this->totalResults = $totalResults;
  }

  /**
   * Add a subitem.
   * @param FacetResultItem $subItem
   */
  public function addSubItem(FacetResultItem $subItem) {
    $this->subItems[] = $subItem;
  }

  /**
   * Does the current facet item has subitems.
   */
  public function hasSubItems() {
    return count($this->subItems) > 0;
  }

  /**
   * Get the label from current item.
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * Get the value for this item.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Get the total results for this item.
   */
  public function getTotalResults() {
    return $this->totalResults;
  }

  /**
   * Get the list of subitems.
   */
  public function getSubItems() {
    return $this->subItems;
  }

  /**
   * Set the label for this facet item.
   */
  public function setLabel($label) {
    $this->label = $label;
  }

}
