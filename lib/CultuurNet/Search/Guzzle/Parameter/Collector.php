<?php
/**
 * @file
 */

namespace CultuurNet\Search\Guzzle\Parameter;

use CultuurNet\Search\Parameter\LocalParameterSerializer;
use CultuurNet\Search\Parameter\Parameter;
use Guzzle\Common\Collection;

/**
 * Puts search parameters in a Guzzle Collection, e.g. a Query.
 */
class Collector
{
    /**
     * @param Parameter[] $parameters
     * @param Collection $collection
     */
    public function addParameters(array $parameters, Collection $collection)
    {
        foreach ($parameters as $parameter) {
            $value = '';
            $localParams = $parameter->getLocalParams();
            if (!empty($localParams)) {
                if (!isset($localParameterSerializer)) {
                    $localParameterSerializer = new LocalParameterSerializer();
                }
                $value = $localParameterSerializer->serialize($localParams);
            }

            $value .= $parameter->getValue();
            $collection->add($parameter->getKey(), $value);
        }
    }
} 
