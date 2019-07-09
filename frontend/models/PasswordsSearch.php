<?php

namespace frontend\models;

use common\models\Projects;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Passwords;

/**
 * PasswordsSearch represents the model behind the search form of `common\models\Passwords`.
 */
class PasswordsSearch extends Passwords
{
    public $project_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['label', 'decs', 'login', 'password', 'url', 'project_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Получаем список ид проектов, по которым нужно сделать выборку паролей
     */
    protected function getFilterAllowProjects()
    {
        $projects = Projects::getMyProjectsIds(); // По умолчанию берем все доступные проекты

        $r = $projects;

        // Если введены ид проектов, то проверяем к каким из них есть доступ
        if ($this->project_id) {
            $r = [];
            if (is_array($this->project_id) && count($this->project_id) > 0) {
                foreach ($this->project_id as $project_id) {
                    if (in_array($project_id, $projects)) {
                        $r[] = $project_id;
                    }
                }
            } else {
                if (in_array($this->project_id, $projects)) {
                    $r[] = $this->project_id;
                }
            }
        }

        return $r;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Passwords::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }

        $projects_id = $this->getFilterAllowProjects();

        // фильтруем список доступных паролей по проектам
        $query
            ->leftJoin('project_password', 'id = password_id')
            ->andWhere(['project_id' => $projects_id])
            ->groupBy('id');

//echo $query->createCommand()->getRawSql();
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'decs', $this->decs])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
