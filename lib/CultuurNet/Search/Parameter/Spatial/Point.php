<?php

namespace CultuurNet\Search\Parameter\Spatial;

use \CultuurNet\Search\Parameter\ParameterInterface;

class Point implements ParameterInterface
{
    /**
     * @var float
     */
    protected $longitude;

    /**
     * @var float
     */
    protected $latitude;

    public function __construct($latitude, $longitude) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getKey() {
        return 'pt';
    }

    public function getValue() {
        return "{$this->latitude},{$this->longitude}";
    }
}
