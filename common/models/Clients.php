<?php

namespace common\models;

use common\behaviors\StatusBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "clients".
 *
 * @property int $id
 * @property int $account_id
 * @property string $name Имя
 * @property string $phone
 * @property string $otherContact
 * @property string $note заметка
 * @property string $status
 */
class Clients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clients';
    }

    public function behaviors()
    {
        return [
            StatusBehavior::class,
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['otherContact'], 'string'],
            [['name', 'phone', 'note'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'account_id' => Yii::t('app', 'Account ID'),
            'name' => Yii::t('app', 'Name'),
            'phone' => Yii::t('app', 'Phone'),
            'otherContact' => Yii::t('app', 'Other Contact'),
            'note' => Yii::t('app', 'Note'),
        ];
    }

    public static function getMyClients()
    {
        return self::findAll(['account_id' => Yii::$app->user->identity->id]);
    }

    public static function getMyClientIds()
    {
        return ArrayHelper::map(self::getMyClients(), 'id', 'id');
    }
    public static function getMyClientNames()
    {
        return ArrayHelper::map(self::getMyClients(), 'id', 'name');
    }

    public function getProjects()
    {
        return $this->hasMany(Projects::class, ['client_id' => 'id']);
    }

    /**
     * Информация о выплатах по клиенту
     * @return array
     */
    public function totalPriceInfo()
    {
        $total = 0; // оплачено
        $diffTotal = 0; // насколько больше/меньше оплачено
        $wait = 0; // активные задачи
        foreach ($this->projects as $project) {
            $info = $project->totalPriceInfo();
            $total += $info['total'];
            $diffTotal += $info['diffTotal'];
            $wait += $info['wait'];
        }

        return [
            'total' => $total,
            'diffTotal' => $diffTotal,
            'wait' => $wait,
        ];
    }
}
