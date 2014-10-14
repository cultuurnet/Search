<?php

namespace CultuurNet\Search;

use Symfony\Component\Console\Input\StringInput;

use \SimpleXMLElement;

class ActivityStatsExtendedEntity {

  /**
   * Activity count types.
   */
  const ACTIVITY_COUNT_COMMENT = 'comment';
  const ACTIVITY_COUNT_REVIEW = 'review';
  const ACTIVITY_COUNT_RECOMMEND = 'recommend';
  const ACTIVITY_COUNT_LIKE = 'like';
  const ACTIVITY_COUNT_ATTEND = 'attend';
  const ACTIVITY_COUNT_PAGE_ADMIN = 'pageadmin';
  const ACTIVITY_COUNT_PAGE_MEMBER = 'pagemember';
  const ACTIVITY_COUNT_PAGE_FOLLOW = 'pagefollow';
  const ACTIVITY_COUNT_FACEBOOK_SHARE = 'facebook_share';

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
   * @var String
   */
  protected $id;

  /**
   * @param string $activityType
   */
  public function getActivityCount($activityType) {
    // @todo check type of $activityType

    if (!isset($this->activityCounts[$activityType])) {
      // No activity set for type in $activityType.
      return NULL;
    }

    return $this->activityCounts[$activityType];
  }

  /**
   * Get the entity.
   * @return \CultureFeed_Cdb_Item_Base
   */
  public function getEntity() {
    return $this->entity;
  }

  /**
   * Get the unique identifier of element.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Get the title of element.
   *
   * @param String $langcode
   */
  public function getTitle($langcode) {

    // Page is a special case.
    if ($this->getType() == 'page') {
      return $this->getEntity()->getName();
    }

    $detail = $this->getEntity()->getDetails()->getDetailByLanguage($langcode);
    if ($detail) {
      return $detail->getTitle();
    }
    return '';
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
    $extendedEntity->id = $cdbItem->getCdbId();

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
    $extendedEntity->id = $cdbItem->getId();

    // Add the different activity counts.
    if (isset($xmlElement->activity)) {
      foreach ($xmlElement->activity as $activity) {
        $activityType = (string) $activity->attributes()->type;
        $extendedEntity->activityCounts[$activityType] = (int) $activity->attributes()->count;
      }
    }

    $extendedEntity->entity = $cdbItem;

    return $extendedEntity;

  }

}
