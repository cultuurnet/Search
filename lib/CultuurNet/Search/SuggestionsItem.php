<?php

namespace CultuurNet\Search;

use \SimpleXMLElement;

class SuggestionsItem {


  /**
   * Object type of the suggestion.
   * @var string
   */
  protected $type;

  /**
   * Title of the suggestion.
   * @var string
   */
  protected $title;

  /**
   * Zipcode of the suggestion.
   * @var string
   */
  protected $zipcode;

  /**
   * Location of the suggestion.
   * @var string
   */
  protected $location;

  /**
   * Cdbid of the suggestion.
   * @var string
   */
  protected $cdbid;

  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function getType() {
    return $this->type;
  }

  public function setType($type) {
    $this->type = $type;
  }

  public function getZipcode() {
    return $this->zipcode;
  }

  public function setZipcode($zipcode) {
    $this->zipcode = $zipcode;
  }

  public function getLocation() {
    return $this->location;
  }

  public function setLocation($location) {
    $this->location = $location;
  }

  public function getCdbid() {
    return $this->cdbid;
  }

  public function setCdbid($cdbid) {
    $this->cdbid = $cdbid;
  }

  /**
   * Construct the suggestion based on the given result xml.
   * @param SimpleXMLElement $xmlElement
   * @return \CultuurNet\Search\SuggestionItem
   */
  public static function fromXml(SimpleXMLElement $xmlElement) {
    
    $suggestionItem = new static();

    $attributes = $xmlElement->attributes();
    $suggestionItem->setCdbid((string) $attributes['cdbid']);
    $suggestionItem->setType((string) $attributes['type']);

    if (!empty($attributes['location'])) {
      $suggestionItem->setLocation((string) $attributes['location']);
      $suggestionItem->setZipcode((string) $attributes['zipcode']);
    }

    $suggestionItem->setTitle((string) $xmlElement);

    return $suggestionItem;
  }

}

