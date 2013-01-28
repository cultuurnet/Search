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
}
