<?php

use common\models\Projects;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$time = time();

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="tasks-index">
    <?php Pjax::begin([
        'id' => 'pjax-job-gridview' . $time,
        'enablePushState' => false
    ]); ?>

    <?= GridView::widget([
        'id'=>'job-gridview' . $time,
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
            'deadline',
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
                        $class = $projectInfo['diffTotal'] >= 0 ? 'green' : 'red';
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-list font-$class"]);
                        return Html::a($icon, ['/projects', 'ProjectsSearch' => ['id' => $key->project_id]]);
                    }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>

</div>
