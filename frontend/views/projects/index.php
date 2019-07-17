<?php

use common\behaviors\StatusBehavior;
use common\models\Clients;
use yii\grid\GridView;
use yii\helpers\Html;

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
                'label' => 'Задачи (Активные/Всего)',
                'value' => function ($model) {
                    return Html::a(
                        $model->getTasks()->andWhere(['status' => StatusBehavior::STATUS_ACTIVE])->count()
                        . ' / ' .
                        $model->getTasks()->count(),
                        [
                            '/tasks',
                            'TasksSearch' => [
                                'status' => StatusBehavior::STATUS_ACTIVE,
                                'project_id' => $model->id
                            ]
                        ]
                    );
                },
                'format' => 'raw'
            ],
            [
                'label' => 'Выплаты',
                'value' => function ($model) {
                    $info = $model->totalPriceInfo();
                    $diffTotalHtml = $info['diffTotal'] > 0 ? '+' . $info['diffTotal'] : $info['diffTotal'];
                    $class = $info['diffTotal'] < 0 ? 'font-red' : 'font-green';
                    return "Всего выплачено: {$info['total']} руб.<br>
                            Переплата: <span class='$class'>$diffTotalHtml  руб.</span><br>
                            В процессе: {$info['wait']}  руб.";
                },
                'format' => 'raw'
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
