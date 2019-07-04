<?php

namespace common\models;

use common\behaviors\StatusBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

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
}
