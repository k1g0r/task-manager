<?php

use common\models\Projects;
use frontend\widgets\editable\EditableWidget;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;


$time = time();
$pjaxId = 'pjax-job-gridview' . $time;
$gridId = 'job-gridview' . $time;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="tasks-index">
    <?php Pjax::begin([
        'id' => $pjaxId,
        'enablePushState' => false
    ]); ?>

    <?= GridView::widget([
        'id' => $gridId,
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
                'value' => function ($model) use ($pjaxId) {
                    return EditableWidget::widget([
                        'name' => 'status',
                        'pk' => $model->id,
                        'value' => $model->status,
                        'title' => Yii::t('app', 'Status'),
                        'inputType' => EditableWidget::INPUT_DROPDOWN_LIST,
                        'source' => $model->getStatuses(),
                        'url' => [
                            '/edit-table/change',
                            'model' => \common\models\Tasks::class,
                            'id' => $model->id,
                            'attribute' => 'status'
                        ],
                        'inputClass' => 'form-control',
                    ]);
                },
                'filter' => $searchModel->getStatuses(),
                'format' => 'raw'
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
