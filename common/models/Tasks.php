<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property int $project_id Проект
 * @property int $parent_id Родительская задача
 * @property string $name
 * @property string $taskText Текст задачи
 * @property string $resultText Что сделано
 * @property int $hoursPrice Стоимость часа работы
 * @property int $time Сколько времени потрачено (минуты)
 * @property int $total Итоговая сумма за задачу
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $waiting_at
 * @property int $payment_at
 */
class Tasks extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    const STATUS_DISABLED = 3;
    const STATUS_MODERATE = 4;
    const STATUS_WAIT = 5;
    const STATUS_WAIT_PAYMENT = 6;
    const STATUS_PAYMENT = 7;

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app','Active task'),
            self::STATUS_DELETED => Yii::t('app','Deleted task'),
            self::STATUS_DISABLED => Yii::t('app','Disabled task'),
            self::STATUS_MODERATE => Yii::t('app','Moderate task'),
            self::STATUS_WAIT => Yii::t('app','Wait task'),
            self::STATUS_WAIT_PAYMENT => Yii::t('app','Waiting for payment task'),
            self::STATUS_PAYMENT => Yii::t('app','Payment task'),
        ];
    }

    public function getStatusTitle()
    {
        return self::getStatuses()[$this->status];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'parent_id', 'hoursPrice', 'time', 'total', 'status'], 'integer'],
            [['created_at', 'updated_at', 'waiting_at', 'payment_at'], 'integer'],
            [['taskText', 'resultText'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_id' => Yii::t('app', 'Project ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'name' => Yii::t('app', 'Name'),
            'taskText' => Yii::t('app', 'Task Text'),
            'resultText' => Yii::t('app', 'Result Text'),
            'hoursPrice' => Yii::t('app', 'Hours Price'),
            'time' => Yii::t('app', 'Time'),
            'total' => Yii::t('app', 'Total'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function getProject()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    public static function getMyTasks()
    {
        return self::findAll(['project_id' => Projects::getMyProjectsIds()]);
    }

    public static function getMyTasksIds()
    {
        return ArrayHelper::map(self::getMyTasks(), 'id', 'id');
    }
    public static function getMyTasksNames()
    {
        return ArrayHelper::map(self::getMyTasks(), 'id', 'name');
    }

    public function beforeSave($insert)
    {
        if ($this->status == self::STATUS_WAIT_PAYMENT) {
            $this->waiting_at = time();
        }
        if ($this->status == self::STATUS_PAYMENT) {
            $this->payment_at = time();
        }
        return parent::beforeSave($insert);
    }

    public function getTimeStr()
    {
        $r = 0;

        if ($this->time) {
            if ($this->time < 60) {
                $r = $this->time . 'м';
            } else {
                $hour = intval($this->time / 60);
                $min = $this->time - ($hour * 60);
                $r = $hour . 'ч ' . $min . 'м';
            }
        }

        return $r;
    }

}
