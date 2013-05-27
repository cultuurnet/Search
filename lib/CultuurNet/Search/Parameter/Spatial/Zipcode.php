<?php

namespace CultuurNet\Search\Parameter\Spatial;

use \CultuurNet\Search\Parameter\ParameterInterface;
use \CultuurNet\Search\Parameter\AbstractParameter;

class Zipcode extends AbstractParameter implements ParameterInterface
{
    /**
     * @var int
     */
    protected $zip;

    /**
     * @var int
     */
    protected $distance;

    public function __construct($zip, $distance = NULL) {
        $this->zip = $zip;
        $this->distance = $distance;
    }

    public function getKey() {
        return 'zipcode';
    }

    public function getValue() {
        $value = $this->zip;
        if (!empty($this->distance)) {
          $value .= '!' . $this->distance . 'km';
        }
        return $value;
    }
}
