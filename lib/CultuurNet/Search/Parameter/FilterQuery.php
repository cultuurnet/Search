<?php

namespace CultuurNet\Search\Parameter;

class FilterQuery extends AbstractParameter
{
    public function __construct($value)
    {
        // @todo check type of $value
        $this->value = $value;

        $this->key = 'fq';
    }
}
