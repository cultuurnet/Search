<?php

namespace CultuurNet\Search;

use SimpleXMLElement;
use \CultuurNet\Search\SuggestionsItem;

class SuggestionsResult implements \Iterator {

  /**
   * Current position in the list.
   * @var int
   */
  protected $position = 0;

  /**
   * @var array
   */
  protected $suggestions = array();

  public function add(SuggestionsItem $suggestion) {
    $this->suggestions[] = $suggestion;
  }

  /**
   * @see Iterator::rewind()
   */
  public function current() {
    return $this->suggestions[$this->position];
  }

  /**
   * @see Iterator::key()
   */
  public function key() {
    return $this->position;
  }

  /**
   * @see Iterator::next()
   */
  public function next() {
    ++$this->position;
  }

  /**
   * @see Iterator::rewind()
   */
  public function rewind() {
    $this->position = 0;
  }

  /**
   * @see Iterator::valid()
   */
  public function valid() {
    return isset($this->suggestions[$this->position]);
  }

  /**
   * Has the current result found any suggestions.
   * @return bool
   */
  public function hasSuggestions() {
    return count($this->suggestions) > 0;
  }

  /**
   * Construct the result based on the given xml.
   * @param SimpleXMLElement $xmlElement
   * @return \CultuurNet\Search\SuggestionsResult
   */
  public static function fromXml(SimpleXMLElement $xmlElement) {

    $result = new static();
    dsm($xmlElement->asXML());
    foreach ($xmlElement->xpath('//suggestion') as $xmlSuggestion) {
      $result->add(SuggestionsItem::fromXml($xmlSuggestion));
    }

    return $result;

  }

}
