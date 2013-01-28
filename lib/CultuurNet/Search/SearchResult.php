<?php

namespace CultuurNet\Search;

use \SimpleXMLElement;

class SearchResult
{
    /**
     * @var integer
     */
    protected $total;

    protected $items;

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

    public static function fromXml(SimpleXMLElement $xmlElement)
    {
        $result = new static();

        $result->total = intval($xmlElement->total);

        $result->items = array();

        /* @var \SimpleXMLElement $xmlItem */
        foreach ($xmlElement->items->item as $xmlItem) {
            $result->items[] = ActivityStatsExtendedEntity::fromXml($xmlItem);
        }

        // @todo How to handle other on-demand components, like facets?

        return $result;
    }
}
