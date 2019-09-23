<?php

namespace frontend\models;

use common\models\Clients;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Projects;

/**
 * ProjectsSearch represents the model behind the search form of `common\models\Projects`.
 */
class ProjectsSearch extends Projects
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'status', 'client_id'], 'integer'],
            [['name', 'note'], 'safe'],
        ];
    }

    /**
     * Получаем список ид клиентов, по которым можно сделать выборку проектов
     */
    protected function getFilterAllowClients()
    {
        $clients = Clients::getMyClientIds(); // По умолчанию берем всех доступных клиентов

        $r = $clients;

        // Если введены ид проектов, то проверяем к каким из них есть доступ
        if ($this->client_id) {
            $r = [];
            if (is_array($this->client_id) && count($this->client_id) > 0) {
                foreach ($this->client_id as $client_id) {
                    if (in_array($client_id, $clients)) {
                        $r[] = $client_id;
                    }
                }
            } else {
                if (in_array($this->client_id, $clients)) {
                    $r[] = $this->client_id;
                }
            }
        }

        return $r;
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

    public function getQuery($params = [])
    {
        $query = Projects::find();

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $query;
        }

        $client_id = $this->getFilterAllowClients();

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'client_id' => $client_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $query;
    }
}
