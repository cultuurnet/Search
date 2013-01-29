<?php

namespace CultuurNet\Search\Guzzle;

use \CultuurNet\Auth\ConsumerCredentials;
use \CultuurNet\Auth\TokenCredentials;
use \CultuurNet\Search\ServiceInterface;
use \CultuurNet\Search\SearchResult;
use \CultuurNet\Search\Parameter\QueryParameterInterface;

use \Guzzle\Http\Client;
use \Guzzle\Plugin\Oauth\OauthPlugin;

use \Guzzle\Http\Url;

use \SimpleXMLElement;
use \DOMDocument;

class Service implements ServiceInterface
{
    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var \CultuurNet\Auth\ConsumerCredentials
     */
    protected $consumerCredentials;

    /**
     * @var \CultuurNet\Auth\TokenCredentials
     */
    protected $tokenCredentials;

    /**
     * @var \Guzzle\Http\Client;
     */
    protected $client;

    /**
     * @param string $endpoint
     * @param \CultuurNet\Auth\ConsumerCredentials $consumerCredentials
     * @param \CultuurNet\Auth\TokenCredentials $tokenCredentials
     */
    public function __construct($endpoint, ConsumerCredentials $consumerCredentials, TokenCredentials $tokenCredentials = null)
    {
        // @todo check type of endpoint
        $this->consumerCredentials = $consumerCredentials;
        $this->tokenCredentials = $tokenCredentials;
        $this->client = null;
        $this->endpoint = $endpoint;
    }

    /**
     * @return \Guzzle\Http\Client
     */
    protected function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client($this->endpoint);

            $config = array(
                'consumer_key' => $this->consumerCredentials->getKey(),
                'consumer_secret' => $this->consumerCredentials->getSecret(),
            );

            if (null !== $this->tokenCredentials) {
                $config += array(
                    'token' => $this->tokenCredentials->getToken(),
                    'token_secret' => $this->tokenCredentials->getSecret(),
                );
            }

            $oAuthPlugin = new OauthPlugin($config);
            $this->client->addSubscriber($oAuthPlugin);
        }

        return $this->client;
    }

    /**
     * @param array $searchParameters
     * @todo maybe $parameters should be a typed object, like a ParameterBag or something,
     * by doing this we can ensure any items inside implement the ParameterInterface
     * @return SearchResult
     */
    public function search($parameters = array())
    {
        $client = $this->getClient();

        $request = $client->get('search');
        $request->getQuery()->setAggregateFunction(array('\Guzzle\Http\QueryString', 'aggregateUsingDuplicates'));

        $qFound = FALSE;

        foreach ($parameters as $parameter) {
            if ('q' == $parameter->getKey()) {
                $qFound = TRUE;
            }

            $value = '';
            if ($parameter instanceof QueryParameterInterface) {
                if (!isset($localParameterSerializer)) {
                    $localParameterSerializer = new \CultuurNet\Search\Parameter\LocalParameterSerializer();
                }
                $value = $localParameterSerializer->serialize($parameter->getLocalParams());
            }

            $value .= $parameter->getValue();

            $request->getQuery()->add($parameter->getKey(), $value);
        }

        if (!$qFound) {
            // @todo throw an exception because the only mandatory parameter is not present
        }

        $response = $request->send();

        // @todo put response into a typed object

        //print $response->getRequest()->getRawHeaders();

        $body = $response->getBody(true);

        // @todo remove debug info
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->loadXML($body);
        $dom->formatOutput = TRUE;

        // for now we need to change the namespace of 'event' elements,
        // as they are not in namespaced with http://www.cultuurdatabank.com/XMLSchema/CdbXSD/3.1/FINAL
        /* @var \DomElement $node */
        foreach ($dom->getElementsByTagName('event') as $node) {
            $newNode = $node->ownerDocument->createElementNS('http://www.cultuurdatabank.com/XMLSchema/CdbXSD/3.1/FINAL', 'event');

            foreach ($node->attributes as $attribute) {
                $newNode->setAttribute($attribute->nodeName, $attribute->nodeValue);
            }

            while ($node->firstChild) {
                $newNode->appendChild($node->firstChild);
            }
            $node->parentNode->replaceChild($newNode, $node);
            //$element->parentNode->replaceChild($newNode, $element);
        }

        $xml = simplexml_import_dom($dom);

        $result = SearchResult::fromXml($xml);

        return $result;
    }
}
