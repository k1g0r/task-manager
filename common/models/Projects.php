<?php

namespace common\models;

use common\behaviors\StatusBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "projects".
 *
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property string $note заметка
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 */
class Projects extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'projects';
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
            [['client_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['note'], 'string'],
            [['name'], 'string', 'max' => 255],
            ['client_id', 'validateClient'],
        ];
    }

    public function validateClient($attr)
    {
        $clients = Clients::getMyClientIds();

        if (!in_array($this->$attr, $clients)) {
            $this->addError($attr, 'Вы не можете привязать проект к данному клиенту');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client_id' => Yii::t('app', 'Client ID'),
            'name' => Yii::t('app', 'Name'),
            'note' => Yii::t('app', 'Note'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

//    public function getMyProjects()
//    {
//        return self::findAll(['client_id' => Clients::getMyClientIds()]);
//    }

}
