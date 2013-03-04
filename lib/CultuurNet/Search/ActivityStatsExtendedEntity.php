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

        // @todo check if type is set
        $type = (string) $xmlElement->attributes()->type;

        /* @var \SimpleXMLElement $child */
        foreach ($xmlElement->children() as $child) {
            //var_dump($child->getName());
            if ($child->getName() == 'activity') {
                $activityType = (string) $child->attributes()->type;
                $extendedEntity->activityCounts[$activityType] = (string) $child;
            }
        }

        foreach ($xmlElement->children('http://www.cultuurdatabank.com/XMLSchema/CdbXSD/3.1/FINAL') as $child) {
            if ($child->getName() === $type) {
                switch ($type) {
                    case 'event':
                        $extendedEntity->entity = CultureFeed_Cdb_Item_Event::parseFromCdbXml($child);
                        break;
                    case 'actor':
                    case 'production':
                        // @todo these cases first require an implementation in the Cdb library
                }
            }
        }

        return $extendedEntity;
    }
}
