<?php

namespace CultuurNet\Search;

use \CultureFeed_Cdb_Item_Event;
use \SimpleXMLElement;

class ActivityStatsExtendedEntity {

    /**
     * array $activityCounts
     */
    protected $activityCounts;

    /**
     * @var mixed
     */
    protected $entity;

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

    public function getEntity() {
        return $this->entity;
    }

    /**
     * @param SimpleXMLElement $xmlElement
     */
    public static function fromXml(SimpleXMLElement $xmlElement) {

      $extendedEntity = new static();
      $type = $xmlElement->getName();

      if (!empty($xmlElement->activities)) {
        foreach ($xmlElement->activities->activity as $activity) {
          $activityType = (string) $child->attributes()->type;
          $extendedEntity->activityCounts[$activityType] = (string) $child;
        }
      }

      switch ($type) {

        case 'event':
          $extendedEntity->entity = CultureFeed_Cdb_Item_Event::parseFromCdbXml($xmlElement);
        break;

        case 'actor':
        case 'production':
          // @todo these cases first require an implementation in the Cdb library

        default:
        return NULL;

      }

        return $extendedEntity;
    }
}
