<?php

namespace CultuurNet\Search;

interface ServiceInterface {
  /**
   * @param array $parameters
   * @return SearchResult
   */
  public function search($parameters = array());
}
