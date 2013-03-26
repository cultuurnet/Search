<?php

namespace CultuurNet\Search\Guzzle;

use \CultuurNet\Auth\ConsumerCredentials;
use \CultuurNet\Auth\TokenCredentials;
use \CultuurNet\Search\ServiceInterface;
use \CultuurNet\Search\SearchResult;
use \CultuurNet\Search\Parameter\QueryParameterInterface;

use \CultuurNet\Search\Parameter\LocalParameterSerializer;

use \Guzzle\Http\Client;
use \Guzzle\Plugin\Oauth\OauthPlugin;

use \Guzzle\Http\Url;

use \SimpleXMLElement;
use \DOMDocument;

class Service implements ServiceInterface {

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
  public function __construct($endpoint, ConsumerCredentials $consumerCredentials, TokenCredentials $tokenCredentials = null) {
    // @todo check type of endpoint
    $this->consumerCredentials = $consumerCredentials;
    $this->tokenCredentials = $tokenCredentials;
    $this->client = null;
    $this->endpoint = $endpoint;
  }

  /**
   * @return \Guzzle\Http\Client
   */
  protected function getClient() {
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
  public function search($parameters = array()) {
    $client = $this->getClient();

    $request = $client->get('search');
    $request->getQuery()->setAggregateFunction(array('\Guzzle\Http\QueryString', 'aggregateUsingDuplicates'));

    $qFound = FALSE;

    foreach ($parameters as $parameter) {
      if ('q' == $parameter->getKey()) {
        $qFound = TRUE;
      }

      $value = '';
      $localParams = $parameter->getLocalParams();
      if (!empty($localParams)) {
        if (!isset($localParameterSerializer)) {
          $localParameterSerializer = new LocalParameterSerializer();
        }
        $value = $localParameterSerializer->serialize($localParams);
      }

      $value .= $parameter->getValue();

      $request->getQuery()->add($parameter->getKey(), $value);
    }

    if (!$qFound) {
      // @todo throw an exception because the only mandatory parameter is not present
    }

    $response = $request->send();
    $body = $response->getBody(true);
    $xml = new SimpleXMLElement($body, 0, FALSE, \CultureFeed_Cdb_Default::CDB_SCHEME_URL);

    return SearchResult::fromXml($xml);

  }
}
