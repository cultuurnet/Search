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
    public function setTags($tags) {
        $this->localParams['tag'] = implode(',', $tags);
    }
}
