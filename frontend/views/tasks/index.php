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
                    $time = $model->getTimeStr();
                    $timeForTotal = $model->getTimeForTotal();
                    $colorNeed = $timeForTotal > $model->time ? 'font-green' : 'font-red';
                    return "Затрачено: $time" .
                        ($timeForTotal ?
                            "<br> Эквивалент к итогу: <span class='$colorNeed'>" . $model->getTimeStr($timeForTotal) . '</span>' :
                            ''
                        );
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'total',
                'value' => function ($model) {
                    $totalForTime = $model->getTotalForTime();
                    $diffTotal = $model->total - $totalForTime;
                    $diffTotalHtml = $diffTotal > 0 ? '+' . $diffTotal : $diffTotal;
                    $colorNeed = $diffTotal < 0 ? 'font-red' : 'font-green';
                    return "Итого: $model->total руб. " . ($diffTotal != $model->total ? "<span class='$colorNeed'>($diffTotalHtml руб.)</span>" : '');
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{paymentStatus} {pass} {view} {update} {delete}',
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
                    },
                    'paymentStatus' => function ($model, $key, $index) {
                        $projectInfo = $key->project->totalPriceInfo();
//                        var_dump($key->project_id); exit();
                        $class = $projectInfo['diffTotal'] >= 0 ? 'green' : 'red';
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-list font-$class"]);
                        return Html::a($icon, ['/projects', 'ProjectsSearch' => ['id' => $key->project_id]]);
                    }
                ]
            ],
        ],
    ]); ?>


</div>
