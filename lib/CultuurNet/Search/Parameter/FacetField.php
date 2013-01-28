<?php

namespace CultuurNet\Search\Parameter;

class FacetField extends AbstractParameter
{
    /**
     * @param $fieldName
     */
    public function __construct($fieldName) {
        $this->key = 'facetField';

        // @todo check type of $fieldName, should be string
        $this->value = $fieldName;
    }
}
