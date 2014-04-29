<?php

namespace CultuurNet\Search;

interface ServiceInterface {

  /**
   * Execute a search call to the service.
   * @param array $parameters
   *   Parameters to be used in the request.
   * @return SearchResult
   */
  public function search($parameters = array());

  /**
   * Execute a pages search call to the service.
   * @param array $parameters
   *   Parameters to be used in the request.
   * @return SearchResult
   */
  public function searchPages($parameters = array());

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
  public function searchSuggestions($search_string, $types = array(), $past = FALSE, $extra_parameters = array());

  /**
   * Load the detail of 1 item.
   * @param string $type
   *   Type of the item. (example: event)
   * @param string $id
   *   ID of the item to load.
   * @return ActivityStatsExtendedEntity
   */
  public function detail($type, $id);
}
