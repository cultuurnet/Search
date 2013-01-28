<?php

namespace CultuurNet\Search\Parameter;

abstract class AbstractQueryParameter extends AbstractParameter implements QueryParameterInterface
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @todo need to think about using a typed object here
     * @param array $params
     */
    public function setLocalParams($params) {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getLocalParams() {
        return $this->params;
    }
}
