<?php

namespace CultuurNet\Search\Parameter\Spatial;

use \CultuurNet\Search\Parameter\AbstractParameter;

class SpatialField extends AbstractParameter
{
    public function __construct($value)
    {
        $this->key = 'sfield';
        $this->value = $value;
    }
}
