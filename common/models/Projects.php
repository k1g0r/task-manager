<?php

namespace common\models;

use common\behaviors\StatusBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

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
 * @property int $hourPrice
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
            [['client_id', 'created_at', 'updated_at', 'status', 'hourPrice'], 'integer'],
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
            'hourPrice' => Yii::t('app', 'hourPrice'),
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getPasswords()
    {
        return $this->hasMany(Passwords::class, ['id' => 'password_id'])
            ->viaTable('project_password', ['project_id' => 'id']);
    }

    public static function getMyProjects($order = null)
    {
        return self::find()
            ->where(['client_id' => Clients::getMyClientIds()])
            ->orderBy($order)
            ->all();
    }

    public static function getMyProjectsIds($order = null)
    {
        return ArrayHelper::map(self::getMyProjects($order), 'id', 'id');
    }
    public static function getMyProjectsNames($order = null)
    {
        return ArrayHelper::map(self::getMyProjects($order), 'id', 'name');
    }

    public function getClient()
    {
        return $this->hasOne(Clients::class, ['id' => 'client_id']);
    }

    public function getTasks()
    {
        return $this->hasMany(Tasks::class, ['project_id' => 'id']);
    }

    /**
     * Информация о выплатах по проекту
     * @return array
     */
    public function totalPriceInfo()
    {
        $total = 0; // оплачено
        $diffTotal = 0; // насколько больше/меньше оплачено
        $wait = 0; // активные задачи
        $del = 0;
        foreach ($this->tasks as $task) {
            if ($task->status == Tasks::STATUS_DELETED) {
                $del += $task->total;
            } elseif ($task->status == Tasks::STATUS_PAYMENT) {
                $totalForTime = $task->getTotalForTime();
                $diff = $totalForTime !== null ? $task->total - $totalForTime : 0;
                $total += $task->total;
                $diffTotal += $diff;
            } else {
                $wait += $task->total;
            }
        }

        return [
            'total' => $total,
            'diffTotal' => $diffTotal,
            'wait' => $wait,
            'del' => $del
        ];
    }

}
