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
  protected $items = array();

  /**
   * @var string
   */
  protected $xml;

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

  /**
   * Construct the search result based on the given result xml.
   * @param SimpleXMLElement $xmlElement
   * @return \CultuurNet\Search\SearchResult
   */
  public static function fromXml(SimpleXMLElement $xmlElement) {

    $result = new static();

    $result->total = intval($xmlElement->nofrecords);
    // Store string version of xml, so the result object can be cached.
    $result->xml = $xmlElement->asXML();

    foreach ($xmlElement as $xmlItem) {
      $entity = ActivityStatsExtendedEntity::fromXml($xmlItem);
      if ($entity) {
        $result->items[] = $entity;
      }
    }

    return $result;

  }

  /**
   * @return string
   */
  public function getXml() {
    return $this->xml;
  }
}
