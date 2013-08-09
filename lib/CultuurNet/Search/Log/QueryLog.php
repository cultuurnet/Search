<?php

namespace CultuurNet\Search\Log;

class QueryLog {

  protected static $instance;

  /**
   * @var array of queries done to api.
   */
  protected $queries = array();

  /**
   * Factory method to get the query log.
   */
  public static function getInstance() {

    if (!isset(self::$instance)) {
      self::$instance = new QueryLog();
    }

    return self::$instance;

  }

  /**
   * @return array
   */
  public function getQueries() {
    return $this->queries;
  }

  public function getQueryCount() {
    return count($this->queries);
  }

  public function add(SearchQuery $query) {
    $this->queries[] = $query;
  }

  public function __toString() {

    $output = '<h3>queries to api (' . $this->getQueryCount() . ')</h3>';

    if ($this->getQueryCount() > 0) {
      $output .= '<pre>';
      foreach ($this->queries as $query) {
        $output .= $query->getUrl() . '<br/>';
      }
      $output .= '</pre>';
    }

    return $output;

  }

}
