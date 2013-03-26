<?php

namespace CultuurNet\Search;

use \SimpleXMLElement;

class SearchResult {
  /**
   * @var integer
   */
  protected $total;

  /**
   * @var ActivityStatsExtendedEntity[]
   */
  protected $items;

  /**
   * @var \SimpleXMLElement
   */
  protected $xml;

  protected function __construct() {
  }

  /**
   * @return ActivityStatsExtendedEntity[]
   */
  public function getItems() {
    return $this->items;
  }

  public function getCurrentCount() {
    return count($this->items);
  }

  /**
   * @return integer
   */
  public function getTotalCount() {
    return $this->total;
  }

  public static function fromXml(SimpleXMLElement $xmlElement) {
    $result = new static();

    $result->total = intval($xmlElement->nofrecords);

    $result->items = array();

    $result->xml = $xmlElement;

    /* @var \SimpleXMLElement $xmlItem */
    foreach ($xmlElement as $xmlItem) {
      $entity = ActivityStatsExtendedEntity::fromXml($xmlItem);
      if ($entity) {
        $result->items[] = $entity;
      }
    }

    // @todo How to handle other on-demand components, like facets?

    return $result;
  }

  /**
   * @return \SimpleXMLElement
   */
  public function getXml() {
    return $this->xml;
  }
}
