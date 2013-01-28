<?php

namespace CultuurNet\Search\Parameter;

class LocalParameterSerializer
{
    public function serialize($localParams) {
        $keyValuePairs = array();

        /* @var \CultuurNet\Search\Parameter\ParameterInterface $localParam */
        foreach ($localParams as $localParam) {
            $key = $localParam->getKey();
            $value = $localParam->getValue();
            // @todo check if backslashes really need to be escaped here
            $value = str_replace('\\', '\\\\', $value);

            if (FALSE !== strpos($value, ' ')) {
                $value = '"' . str_replace('"', '\"', $value) . '"';
            }
            // @todo do we need more escaping, chars like } maybe?
            $params[] = "{$key}={$value}";
        }

        $paramString = implode(' ', $keyValuePairs);

        return "{!{$paramString}}";
    }
}
