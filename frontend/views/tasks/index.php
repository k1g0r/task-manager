<?php

use common\models\Projects;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tasks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'project_id',
                'value' => function ($model) {
                    return $model->project ? $model->project->name : null;
                },
                'format' => 'raw',
                'filter' => Projects::getMyProjectsNames()
            ],
//            [
//                'attribute' => 'parent_id',
//                'value' => function ($model) {
//                    return $model->parent ? $model->parent->name : null;
//                },
//                'format' => 'raw',
////                'filter' => Projects::getMyProjectsNames()
//            ],
            'name',
            [
                'attribute' => 'status',
                'value' => 'statusTitle',
                'filter' => $searchModel->getStatuses(),
            ],
            [
                'attribute' => 'time',
                'value' => function ($model) {
                    return $model->getTimeStr();
                }
            ],
            [
                'attribute' => 'total',
                'value' => function ($model) {
                    return $model->total . ' руб.';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{pass} {view} {update} {delete}',
                'buttons' => [
                    'pass' => function ($model, $key, $index) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-lock"]);
                        return Html::a(
                            $icon,
                            ['passwords/index', 'PasswordsSearch' => ['project_id' => $key->project_id]],
                            [
                                'data-toggle' => 'modal',
                                'data-target' => '#modalBoxAjax'
                            ]
                        );
                    }
                ]
            ],
        ],
    ]); ?>


</div>
