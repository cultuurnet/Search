<?php

namespace CultuurNet\Search;

use \SimpleXMLElement;

class ActivityStatsExtendedEntity {

  /**
   * Activity count types.
   */
  const ACTIVITY_COUNT_COMMENT = 'comment';
  const ACTIVITY_COUNT_RECOMMEND = 'recommend';
  const ACTIVITY_COUNT_LIKE = 'like';
  const ACTIVITY_COUNT_ATTEND = 'attend';

  /**
   * array $activityCounts
   */
  protected $activityCounts;

  /**
   * @var CultureFeed_Cdb_Item_Base
   */
  protected $entity;

  /**
   * @var string
   */
  protected $type;

  /**
   * @param string $activityType
   */
  public function getActivityCount($activityType) {
    // @todo check type of $activityType

    if (!isset($this->activityCounts[$activityType])) {
      // @todo throw exception
    }

    return $this->activityCounts[$activityType];
  }

  /**
   * Get the entity.
   * @return CultureFeed_Cdb_Item_Base
   */
  public function getEntity() {
    return $this->entity;
  }

  /**
   * Get the type of element.
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Create an extended entity based on a given xmlElement.
   * @param SimpleXMLElement $xmlElement
   */
  public static function fromXml(SimpleXMLElement $xmlElement) {

    $cdbItem = \CultureFeed_Cdb_Default::parseItem($xmlElement);
    if (!$cdbItem) {
      return NULL;
    }

    $extendedEntity = new static();
    $extendedEntity->type = $xmlElement->getName();

    // Add the different activity counts.
    if (!empty($xmlElement->activities)) {
      foreach ($xmlElement->activities->activity as $activity) {
        $activityType = (string) $activity->attributes()->type;
        $extendedEntity->activityCounts[$activityType] = (int) $activity->attributes()->count;
      }
    }

    $extendedEntity->entity = $cdbItem;

    return $extendedEntity;

  }

  /**
   * Create an extended entity based on a given xmlElement.
   * @param \SimpleXMLElement $xmlElement
   *   Pages search xml element.
   */
  public static function fromPagesXml(\SimpleXMLElement $xmlElement) {

    $attributes = $xmlElement->attributes();

    $type = (string) $attributes['type'];
    if ($type != 'page') {
      return NULL;
    }

    $cdbItem = \CultureFeed_Cdb_Item_Page::parseFromCdbXml($xmlElement->page);
    $extendedEntity = new static();
    $extendedEntity->type = (string) $attributes['type'];

    // Add the different activity counts.
    if (!empty($xmlElement->activity)) {
      foreach ($xmlElement>activity as $activity) {
        $activityType = (string) $activity->attributes()->type;
        $extendedEntity->activityCounts[$activityType] = (int) $activity->attributes()->count;
      }
    }

    $extendedEntity->entity = $cdbItem;

    return $extendedEntity;

  }

}
