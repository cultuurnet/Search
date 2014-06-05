<?php
/**
 * @file
 */

namespace CultuurNet\Search\Component\Facet;

use \CultuurNet\Search\Parameter\FacetField;


class FacetFactory
{
    /**
     * @param \SimpleXMLElement $xml
     *
     * @return Facet[]
     */
    public function createFromXML(\SimpleXMLElement $xml, $facets = array())
    {
        if (!empty($xml->facets)) {
            /** @var \SimpleXMLElement $facetElement */
            foreach ($xml->facets->facet as $facetElement) {

                $facetAttributes = $facetElement->attributes();
                $field = (string) $facetAttributes['field'];

                // Category is a special facet. This is a fake facet that returns
                // all the results for every possible category facet.
                // Map it to the correct facet.
                if ($field == 'category') {
                    $field = 'category_' . $facetAttributes['domain'] . '_id';
                    if (!isset($facets[$field])) {
                        $facets[$field] = new Facet($field, new FacetField($field));
                    }
                }

                if (isset($facets[$field])) {

                    /** @var Facet $facet */
                    $facet = $facets[$field];

                    $item = $this->createResultItem($facetElement);
                    $this->createFacetResultSubItems($facetElement, $item);

                    $facet->getResult()->addItem($item);
                }
            }
        }

        return $facets;
    }

    /**
     * @param \SimpleXMLElement $facetElement
     * @param FacetResultItem $facetResultItem
     */
    protected function createFacetResultSubItems(\SimpleXMLElement $facetElement, FacetResultItem $facetResultItem)
    {
        foreach ($facetElement->facet as $facetSubItemElement) {
            $subItem = $this->createResultItem($facetSubItemElement);

            $this->createFacetResultSubItems($facetSubItemElement, $subItem);

            $facetResultItem->addSubItem($subItem);
        }
    }

    /**
     * @param \SimpleXMLElement $facetElement
     * @return FacetResultItem
     */
    protected function createResultItem(\SimpleXMLElement $facetElement)
    {
        $facetAttributes = $facetElement->attributes();
        $value = isset($facetAttributes['id']) ? (string) $facetAttributes['id'] : (string) $facetAttributes['name'];
        $facetResultItem = new FacetResultItem((string) $facetAttributes['name'], $value, (int) $facetAttributes['total']);

        return $facetResultItem;
    }
} 
