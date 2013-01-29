<?php

namespace CultuurNet\Search\Component\Facet;

use \CultuurNet\Search\Parameter\ParameterInterface;

class Facet {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var
     */
    protected $parameter;

    /**
     * @var FacetResult
     */
    protected $result;

    /**
     * @param string $key
     * @param \CultuurNet\Search\Parameter\ParameterInterface $parameter
     */
    public function __construct($key, ParameterInterface $parameter)
    {
        $this->key = $key;
        $this->parameter = $parameter;

        $this->result = new FacetResult();
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return FacetResult
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return \CultuurNet\Search\Parameter\ParameterInterface
     */
    public function getParameter() {
        return $this->parameter;
    }
}
