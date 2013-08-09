<?php

namespace CultuurNet\Search\Log;

class SearchQuery {

  protected $url;
  protected $time;
  private $startTime;

  public function __construct($url) {
    $this->url = $url;
    $this->startTime = microtime(TRUE);
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl($url) {
    $this->url = $url;
  }

  public function getTime() {
    return $this->time;
  }

  public function setTime($time) {
    $this->time = $time;
  }

  /**
   * Query has received a result, stop the query.
   */
  public function resultReceived() {

  }

  public function __toString() {

    $output = '<h3>queries to api (' . $this->getQueryCount() . ')</h3>';

    if ($this->getQueryCount() > 0) {
      $output .= '<pre>';
      foreach ($this->queries as $query) {
        $output .= $query . '<br/>';
      }
      $output .= '</pre>';
    }

    return $output;
  }

}
