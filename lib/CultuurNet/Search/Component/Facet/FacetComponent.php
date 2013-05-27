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

  /**
   * @return Facet[]
   */
  public function getFacets() {
    return $this->facets;
  }

  public function obtainResults(SearchResult $result) {

    $xmlElement = $result->getXmlElement();

    if (!empty($xmlElement->facets)) {
      foreach ($xmlElement->facets->facet as $facetElement) {

        $facetAttributes = $facetElement->attributes();
        $field = (string) $facetAttributes['field'];

        // Category is a special facet. This is a fake facet that returns
        // all the results for every possible category facet.
        // Map it to the correct facet.
        if ($field == 'category') {
          $field = 'category_' . $facetAttributes['domain'] . '_name';
          if (!isset($this->facets[$field])) {
            $this->facets[$field] = new Facet($field, new FacetField($field));
          }
        }

        if (isset($this->facets[$field])) {

          $facet = $this->facets[$field];

          $facetResultItem = new FacetResultItem((string) $facetAttributes['name'], (int) $facetAttributes['total']);

          // Check if this facet has children.
          //if (!empty($facetElement->facet)) {
          foreach ($facetElement->facet as $childFacetElement) {
            $childFacetAttributes = $childFacetElement->attributes();
            $facetResultItem->addSubItem(new FacetResultItem((string) $childFacetAttributes['name'], (int) $childFacetAttributes['total']));
          }

          $facet->getResult()->addItem($facetResultItem);

        }

      }
    }

  }
}
