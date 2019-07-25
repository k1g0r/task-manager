<?php

namespace frontend\models;

use common\models\Projects;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tasks;

/**
 * TasksSearch represents the model behind the search form of `common\models\Tasks`.
 */
class TasksSearch extends Tasks
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'parent_id', 'hoursPrice', 'time', 'total', 'status'], 'integer'],
            [['name', 'taskText', 'resultText'], 'safe'],
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
     * Получаем список ид проектов, по которым нужно сделать выборку задач
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

    public function getQuery($params)
    {
        $query = Tasks::find();

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $query;
        }

        $project_id = $this->getFilterAllowProjects();

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'project_id' => $project_id,
            'parent_id' => $this->parent_id,
            'hoursPrice' => $this->hoursPrice,
            'time' => $this->time,
            'total' => $this->total,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'taskText', $this->taskText])
            ->andFilterWhere(['like', 'resultText', $this->resultText]);

        return $query;

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
        $query = $this->getQuery($params);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
