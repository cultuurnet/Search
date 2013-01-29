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
        // @todo get results from xml and fill the # facets
        foreach ($this->facets as $facet) {
            $xml = $result->getXml();

            if (!empty($xml->facets)) {
                foreach ($xml->facets->facet as $facetElement) {
                    if ((string)$facetElement->field === $facet->getKey()) {
                        $facet->getResult()->addItem((string) $facetElement->name, (string) $facetElement->count);
                    }
                }
            }
        }
    }
}
