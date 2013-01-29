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

    /**
     * @param array $tags
     */
    public function setTags($tags)
    {
        $this->localParams['tag'] = implode(',', $tags);
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setLocalParam($key, $value)
    {
        // @todo type checking
        // @todo proper escaping?
        $this->localParams[$key] = $value;
    }
}
