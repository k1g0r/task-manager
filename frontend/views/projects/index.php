<?php

use yii\grid\GridView;
use yii\helpers\Html;
use common\models\Clients;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ProjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Projects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projects-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Projects'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                    'label' => 'Client',
                    'attribute' => 'client_id',
                    'value' => function ($model) {
                        return $model->client->name;
                    },
                'filter' => Clients::getMyClientNames(),
],
            'name',
            'note:ntext',
            [
                'attribute' => 'status',
                'value' => 'statusTitle',
                'filter' => $searchModel->getStatuses(),
            ],
            [
                'label' => 'Активные задачи',
                'value' => function ($model) {
                    return 0;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{pass} {view} {update} {delete}',
                'buttons' => [
                    'pass' => function ($model, $key, $index) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-lock"]);
                        return Html::a($icon, ['passwords/index', 'PasswordsSearch' => ['project_id' => $key->id]]);
                    }
                ]
            ],
        ],
    ]); ?>


</div>
