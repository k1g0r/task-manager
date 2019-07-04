<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;

class StatusBehavior extends Behavior
{
    const STATUS_DISABLED = 5;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    const STATUS_DRAFT = 3;
    const STATUS_MODERATE = 4;

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app','Active'),
            self::STATUS_DISABLED => Yii::t('app','Disabled'),
            self::STATUS_DELETED => Yii::t('app','Deleted'),
            self::STATUS_DRAFT => Yii::t('app','Draft'),
            self::STATUS_MODERATE => Yii::t('app','Moderate'),
        ];
    }

    public function getStatusTitle()
    {
        return self::getStatuses()[$this->owner->status];
    }

    public function isActive()
    {
        return $this->owner->status == self::STATUS_ACTIVE;
    }

    public function isDraft()
    {
        return $this->owner->status == self::STATUS_DRAFT;
    }
}
