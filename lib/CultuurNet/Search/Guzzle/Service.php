<?php

namespace CultuurNet\Search\Guzzle;

use \CultuurNet\Auth\Guzzle\OAuthProtectedService;

use \CultuurNet\Search\ServiceInterface;
use \CultuurNet\Search\SearchResult;
use \CultuurNet\Search\SuggestionsResult;
use \CultuurNet\Search\ActivityStatsExtendedEntity;
use \CultuurNet\Search\Parameter\Query;
use \CultuurNet\Search\Parameter\Type;
use \CultuurNet\Search\Parameter\Title;
use \CultuurNet\Search\Parameter\LocalParameterSerializer;

use \SimpleXMLElement;

class Service extends OAuthProtectedService implements ServiceInterface {

  /**
   * Execute a search call to the service.
   * @param array $parameters
   *   Parameters to be used in the request.
   * @return SearchResult
   */
  public function search($parameters = array()) {
    $response = $this->executeSearch('search', $parameters);
    return SearchResult::fromXml(new SimpleXMLElement($response->getBody(true), 0, false, \CultureFeed_Cdb_Default::CDB_SCHEME_URL));
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
   * Load the detail of 1 item.
   * @param string $type
   *   Type of the item. (example: event)
   * @param string $id
   *   ID of the item to load.
   * @return ActivityStatsExtendedEntity
   */
  public function detail($type, $id) {

    $response = $this->executeSearch('detail/' . $type . '/' . $id);
    $xmlElement = new SimpleXMLElement($response->getBody(true), 0, false, \CultureFeed_Cdb_Default::CDB_SCHEME_URL);
    $detail = $xmlElement->{$type};
    if (!empty($detail[0])) {
      return ActivityStatsExtendedEntity::fromXml($detail[0]);
    }
  }

  /**
   * Get a list of suggestions from the given search string.
   * @param string $search
   *   String to get suggestions for.
   * @param array $types
   *   Types to search for. Example page.
   * @param bool $past
   *   Also search suggestions for past events.
   */
  public function searchSuggestions($search_string, $types = null, $past = FALSE) {

    $client = $this->getClient();
    $request = $client->get($search_path = empty($types) ? 'search/suggest' : 'search/suggest/item');
    $request->getQuery()->setAggregateFunction(array('\Guzzle\Http\QueryString', 'aggregateUsingDuplicates'));

    if (!empty($types)) {

      foreach ($types as $type) {
        $parameter = new Type($type);
        $request->getQuery()->add($parameter->getKey(), $parameter->getValue());
      }

      $parameter = new Title($search_string);
      $request->getQuery()->add($parameter->getKey(), $parameter->getValue());

    }
    else {
      $parameter = new Query($search_string);
      $request->getQuery()->add($parameter->getKey(), $parameter->getValue());
    }

    if ($past) {
      $request->getQuery()->add('past', 'true');
    }

    $response = $request->send();

    $xmlElement = new SimpleXMLElement($response->getBody(true), 0, false, \CultureFeed_Cdb_Default::CDB_SCHEME_URL);

    return SuggestionsResult::fromXml($xmlElement);

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
  private function executeSearch($path, $parameters = array()) {

    $client = $this->getClient();

    $request = $client->get($path);
    $request->getQuery()->setAggregateFunction(array('\Guzzle\Http\QueryString', 'aggregateUsingDuplicates'));

    $qFound = false;

    foreach ($parameters as $parameter) {

      if ('q' == $parameter->getKey()) {
        $qFound = true;
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

    $result = $request->send();

    return $result;
  }

}
