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
   * @var SimpleXMLElement
   */
  protected $xmlElement;

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
   * @return SimpleXMLElement
   */
  public function getXmlElement() {
    return $this->xmlElement;
  }

  /**
   * Set the xml element.
   */
  public function setXmlElement($element) {
    $this->xmlElement = $element;
  }

  /**
   * Construct the search result based on the given result xml.
   * @param SimpleXMLElement $xmlElement
   * @return \CultuurNet\Search\SearchResult
   */
  public static function fromXml(SimpleXMLElement $xmlElement) {

    $result = new static();

    $result->total = intval($xmlElement->nofrecords);

    // Store
    $result->xmlElement = $xmlElement;

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
   * Construct the search result based on the given result xml for pages.
   * @param SimpleXMLElement $xmlElement
   * @return \CultuurNet\Search\SearchResult
   */
  public static function fromPagesXml(SimpleXMLElement $xmlElement) {

    $result = new static();

    $result->total = intval($xmlElement->total);
    // Store string version of xml, so the result object can be cached.
    $result->xml = $xmlElement->asXML();

    foreach ($xmlElement->items->item as $xmlItem) {
      $entity = ActivityStatsExtendedEntity::fromPagesXml($xmlItem);
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
