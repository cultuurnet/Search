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

use Guzzle\Http\QueryAggregator\DuplicateAggregator;
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
   * Get a list of deleted items.
   * @param int $deleted_since
   *   Timestamp to filter on.
   * @param int $rows
   *   Total rows to get.
   * @param int $start
   *   Starting page.
   * @return Array of deleted id's.
   */
  public function getDeletions($deleted_since = NULL, $rows = NULL, $start = NULL) {

    $client = $this->getClient();

    $request = $client->get('search/deleted');

    if (!empty($deleted_since)) {
      $request->getQuery()->add('deletedsince', $deleted_since);
    }

    if ($rows !== NULL) {
      $request->getQuery()->add('rows', $rows);
    }

    if ($start !== NULL) {
      $request->getQuery()->add('start', $start);
    }

    $response = $request->send();
    $xmlElement = new SimpleXMLElement($response->getBody(true));

    $deletedIds = array();
    if (!empty($xmlElement->items)) {
      foreach ($xmlElement->items->item as $deletedItem) {
        $deletedIds[] = (string) $deletedItem;
      }
    }

    return $deletedIds;

  }

  /**
   * Get a list of suggestions from the given search string.
   * @param string $search
   *   String to get suggestions for.
   * @param array $types
   *   Types to search for. Example page.
   * @param bool $past
   *   Also search suggestions for past events.
   * @param array $extra_parameters
   *   Extra parameters to add to the search query.
   */
  public function searchSuggestions($search_string, $types = null, $past = FALSE, $extra_parameters = array()) {

    $client = $this->getClient();
    $request = $client->get($search_path = empty($types) ? 'search/suggest' : 'search/suggest/item');
    $request->getQuery()->setAggregator(new DuplicateAggregator());

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

    // Add additional requested parameters.
    foreach ($extra_parameters as $parameter) {

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
    $request->getQuery()->setAggregator(new DuplicateAggregator());

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
