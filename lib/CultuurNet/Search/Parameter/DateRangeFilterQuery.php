<?php

namespace CultuurNet\Search\Parameter;

use \CultuurNet\Search\Parameter\FilterQuery;

class DateRangeFilterQuery extends FilterQuery {

  /**
   * Field to query.
   * @param string
   */
  protected $field;

  /**
   * Startdate to search on.
   * @param int
   */
  protected $startDate;

  /**
   * End date to search on.
   * @param int
   */
  protected $endDate;

  public function __construct($field, $startDate, $endDate) {
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->field = $field;
    $this->key = 'fq';
  }

  /**
   * Return the search value.
   */
  public function getValue() {
    $startRange = $this->startDate == '*' ? $this->startDate : date('Y-m-d\TH:i:s\Z', $this->startDate);
    $endRange = $this->endDate == '*' ? $this->endDate : date('Y-m-d\TH:i:s\Z', $this->endDate);
    return $this->field . ':[' . $startRange . ' TO ' . $endRange . ']';
  }

}
