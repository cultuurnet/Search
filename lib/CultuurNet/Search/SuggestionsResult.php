<?php

namespace CultuurNet\Search;

use \SimpleXMLElement;

class SuggestionsResult {

  /**
   * @var array
   */
  protected $suggestions = array();

  /**
   * @var \SimpleXMLElement
   */
  protected $xml;

  /**
   * @return ActivityStatsExtendedEntity[]
   */
  public function getSuggestions() {
    return $this->suggestions;
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
    $result->xml = $xmlElement;

    foreach ($xmlElement->xpath('//suggestion') as $xmlSuggestion) {
      $result->suggestions[] = (string)$xmlSuggestion;
    }

    return $result;

  }

  /**
   * @return \SimpleXMLElement
   */
  public function getXml() {
    return $this->xml;
  }
}
