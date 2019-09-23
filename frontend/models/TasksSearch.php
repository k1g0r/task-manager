<?php

namespace frontend\models;

use common\models\Clients;
use common\models\Projects;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tasks;

/**
 * TasksSearch represents the model behind the search form of `common\models\Tasks`.
 */
class TasksSearch extends Tasks
{
    public $client_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'parent_id', 'hoursPrice', 'time', 'total', 'status', 'client_id'], 'integer'],
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
    protected function getFilterAllowClients()
    {
        $clients = Clients::getMyClientIds(); // По умолчанию берем всех доступных клиентов

        $clientsAllow = [];
        // Если введены ид клиентов, то проверяем к каким из них есть доступ
        if ($this->client_id) {
            if (is_array($this->client_id) && count($this->client_id) > 0) {
                foreach ($this->client_id as $client_id) {
                    if (in_array($client_id, $clients)) {
                        $clientsAllow[] = $client_id;
                    }
                }
            } else {
                if (in_array($this->client_id, $clients)) {
                    $clientsAllow[] = $this->client_id;
                }
            }
        }

        return $clientsAllow;
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

    public function getQuery($params = [])
    {
        $query = Tasks::find();

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $query;
        }

        $project_id = $this->getFilterAllowProjects();

        $client_id = $this->getFilterAllowClients();

        if ($project_id) {
            $query->innerJoin('projects', 'project_id = projects.id');
            $query->andWhere(['projects.id' => $project_id]);
        }

        if ($client_id) {
            $query->innerJoin('clients', 'projects.client_id = clients.id');
            $query->andWhere(['clients.id' => $client_id]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tasks.id' => $this->id,
//            'project_id' => $project_id,
            'tasks.parent_id' => $this->parent_id,
            'tasks.hoursPrice' => $this->hoursPrice,
            'tasks.time' => $this->time,
            'tasks.total' => $this->total,
            'tasks.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'tasks.name', $this->name])
            ->andFilterWhere(['like', 'tasks.taskText', $this->taskText])
            ->andFilterWhere(['like', 'tasks.resultText', $this->resultText]);

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
