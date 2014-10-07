<?php

namespace CultuurNet\Search\Component\Facet;

use \CultuurNet\Search\Parameter\FacetField;
use \CultuurNet\Search\SearchResult;
use \SimpleXMLElement;

class FacetComponent {

  /**
   * @var Facet[]
   */
  protected $facets = array();

  /**
   * @param $field
   * @return \CultuurNet\Search\Parameter\FacetField
   */
  public function facetField($field) {
    // @todo check if field isn't used yet
    $this->facets[$field] = new Facet($field, new FacetField($field));

    return $this->facets[$field]->getParameter();
  }

  public function setFacetField($key, FacetField $field) {
    $this->facets[$key] = new Facet($key, $field);
  }

  /**
   * @return Facet[]
   */
  public function getFacets() {
    return $this->facets;
  }

  public function obtainResults(SearchResult $result) {
    $xmlElement = $result->getXmlElement();
    $factory = new FacetFactory();

    $this->facets = $factory->createFromXML($xmlElement, $this->facets);
  }
}
