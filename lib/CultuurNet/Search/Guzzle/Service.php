<?php

namespace CultuurNet\Search\Guzzle;

use CultuurNet\Search\Parameter\Type;

use \CultuurNet\Auth\ConsumerCredentials;
use \CultuurNet\Auth\TokenCredentials;
use \CultuurNet\Search\ServiceInterface;
use \CultuurNet\Search\SearchResult;
use \CultuurNet\Search\SuggestionsResult;

use \CultuurNet\Search\Parameter\Query;
use \CultuurNet\Search\Parameter\QueryParameterInterface;
use \CultuurNet\Search\Parameter\LocalParameterSerializer;

use \Guzzle\Http\Client;
use \Guzzle\Plugin\Oauth\OauthPlugin;

use \Guzzle\Http\Url;

use \SimpleXMLElement;

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
   * Execute a search call to the service.
   * @param array $parameters
   *   Parameters to be used in the request.
   * @return SearchResult
   */
  public function search($parameters = array()) {
    $response = $this->executeSearch('search', $parameters);
    return SearchResult::fromXml(new SimpleXMLElement($response->getBody(true), 0, FALSE, \CultureFeed_Cdb_Default::CDB_SCHEME_URL));
  }

  /**
   * Execute a pages search call to the service.
   * @param array $parameters
   *   Parameters to be used in the request.
   * @return SearchResult
   */
  public function searchPages($parameters = array()) {
    $response = $this->executeSearch('search/page', $parameters);
    return SearchResult::fromPagesXml(new SimpleXMLElement($response->getBody(true)));
  }

  /**
   * Perform a search call to the service
   * @param $path
   *   Path to call
   * @param array $parameters
   *   Parameters to be used in the request.
   * @return SimpleXMLElement
   *   Xml returned from the call.
   */
  private function executeSearch($path, $parameters) {

    $client = $this->getClient();

    $request = $client->get($path);
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

    return $request->send();

  }


  /**
   * Get a list of suggestions from the given search string.
   * @param string $search
   *   String to get suggestions for.
   * @param string $type
   *   Type to search for. Example page.
   */
  public function searchSuggestions($search_string, $type = NULL) {

    $client = $this->getClient();
    $request = $client->get($search_path = empty($type) ? 'search/suggest' : 'search/suggest/item');
    $parameter = new Query($search_string);
    $request->getQuery()->add($parameter->getKey(), $parameter->getValue());

    if (!empty($type)) {
      $parameter = new Type($type);
      $request->getQuery()->add($parameter->getKey(), $parameter->getValue());
    }

    $response = $request->send();
    $xml = new SimpleXMLElement($response->getBody(true), 0, FALSE, \CultureFeed_Cdb_Default::CDB_SCHEME_URL);

    return SuggestionsResult::fromXml($xml);

  }

}
