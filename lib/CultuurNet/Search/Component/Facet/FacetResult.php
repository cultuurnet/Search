<?php

namespace CultuurNet\Search\Component\Facet;

class FacetResult
{
    /**
     * @var array
     */
    protected $items = array();

    public function addItem($name, $number) {
        $this->items[$name] = $number;
    }

    public function getItems() {
        return $this->items;
    }
}
