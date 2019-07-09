<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "passwords".
 *
 * @property int $id
 * @property string $label
 * @property string $decs
 * @property string $login
 * @property string $password
 * @property string $projectIds
 * @property string $url
 */
class Passwords extends \yii\db\ActiveRecord
{

    protected $project_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'passwords';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['label', 'decs', 'login', 'password', 'url'], 'string', 'max' => 255],
            ['projectIds', 'required'],
            ['projectIds', 'safe'],
            ['projectIds', 'validateProject'],
        ];
    }

    public function validateProject($attr)
    {
        $projects = Projects::getMyProjectsIds();
        $projects_id = $this->$attr;

        if (is_array($projects_id) && count($projects_id) > 0) {
            foreach ($projects_id as $v) {
                if (!in_array($v, $projects)) {
                    $this->addError($attr, 'Вы не можете привязать пароль к данному проекту');
                    break;
                }
            }
        } else {
            $this->addError($attr, 'Вы должны указать от какого проекта данный пароль');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_id' => Yii::t('app', 'Project'),
            'label' => Yii::t('app', 'Label'),
            'decs' => Yii::t('app', 'Decs'),
            'login' => Yii::t('app', 'Login'),
            'password' => Yii::t('app', 'Password'),
            'url' => Yii::t('app', 'Url'),
        ];
    }

    public function getProjectIds()
    {
        $r = $this->project_ids;
        if (!$r) {
            $r = ArrayHelper::map($this->projects, 'id', 'id');
            $this->project_ids = $r;
        }

        return $r;
    }

    public function setProjectIds($arr)
    {
        $this->project_ids = $arr;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getProjects()
    {
        return $this->hasMany(Projects::class, ['id' => 'project_id'])
            ->viaTable('project_password', ['password_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->unlinkAll('projects', true);
        foreach ($this->projectIds as $project_id) {
            $this->link('projects', Projects::findOne($project_id));
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete()
    {
        $this->unlinkAll('projects', true);
        return parent::beforeDelete();
    }

}
