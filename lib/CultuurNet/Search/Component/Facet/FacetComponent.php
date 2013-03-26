<?php

namespace CultuurNet\Search\Component\Facet;

use \CultuurNet\Search\Parameter\FacetField;
use \CultuurNet\Search\SearchResult;

class FacetComponent
{
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

      $xml = $result->getXml();

      if (!empty($xml->facets)) {
        foreach ($xml->facets->facet as $facetElement) {

          $facetAttributes = $facetElement->attributes();
          if (isset($this->facets[(string) $facetAttributes['field']])) {
            $facet = $this->facets[(string) $facetAttributes['field']];
            $facet->getResult()->addItem((string) $facetAttributes['name'], (int) $facetAttributes['total']);
          }

        }
      }

    }
}
