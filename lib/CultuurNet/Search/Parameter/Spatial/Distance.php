<?php

namespace CultuurNet\Search\Parameter\Spatial;

use \CultuurNet\Search\Parameter\AbstractParameter;

class Distance extends AbstractParameter
{
    /**
     * @param float $value distance in kilometers
     */
    public function __construct($kilometers)
    {
       $this->key = 'd';
       $this->value = $kilometers;
    }
}
