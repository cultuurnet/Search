<?php

namespace CultuurNet\Search\Parameter;

class Sort implements ParameterInterface
{
    const DIRECTION_ASC = 'asc';
    const DIRECTION_DESC = 'desc';

    /**
     * @var string
     */
    protected $field;

    /**
     * @var string
     */
    protected $direction;

    protected $localParams = array();

    /**
     * @param $field
     * @param string $directionp
     */
    public function __construct($field, $direction = self::DIRECTION_ASC)
    {
        $this->field = $field;
        $this->direction = $direction;
    }

    public function getKey() {
        return 'sort';
    }

    public function getValue() {
        return $this->field . ' ' . $this->direction;
    }

    public function getLocalParams() {
        return $this->localParams;
    }
}
