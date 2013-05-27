<?php

namespace CultuurNet\Search\Parameter\Spatial;

use \CultuurNet\Search\Parameter\FilterQuery;

class GeoFilterQuery extends FilterQuery {

  /**
   * Point to search on.
   * @param \CultuurNet\Search\Parameter\Spatial\Point
   */
  protected $point;

  /**
   * Distance from the geofilter query.
   * @param \CultuurNet\Search\Parameter\Spatial\Distance
   */
  protected $distance;

  /**
   * Field to query.
   * @param \CultuurNet\Search\Parameter\Spatial\SpatialField
   */
  protected $field;

  public function __construct(Point $point, Distance $distance, SpatialField $field) {
    $this->point = $point;
    $this->distance = $distance;
    $this->field = $field;
    $this->key = 'fq';
  }

  /**
   * Return the search value. Format: {!geofilt%20pt=3.720739,51.036906%20sfield=physical_gis%20d=5}
   */
  public function getValue() {

    $value = urlencode('{') . '!geofilt ';
    $value .= $this->point->getKey() . '='. $this->point->getValue();
    $value .= ' ' . $this->field->getKey() . '=' . $this->field->getValue();
    $value .= ' ' . $this->distance->getKey() . '=' . $this->distance->getValue();
    $value .= urlencode('}');

    return $value;
  }

}
