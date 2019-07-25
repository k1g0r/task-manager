<?php

use common\models\Projects;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\Tabs;
use common\models\Tasks;

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

    <?= Tabs::widget([
    'items' => [
        [
            'label' => 'Активные' . " ({$count[Tasks::STATUS_ACTIVE]})",
            'url' => ['ajax-index', 'TasksSearch' => ['status' => Tasks::STATUS_ACTIVE]],
        ],
        [
            'label' => 'Проверяющиеся' . " ({$count[Tasks::STATUS_MODERATE]})",
            'url' => ['ajax-index', 'TasksSearch' => ['status' => Tasks::STATUS_MODERATE]],
        ],
        [
            'label' => 'Ожидание оплаты' . " ({$count[Tasks::STATUS_WAIT_PAYMENT]})",
            'url' => ['ajax-index', 'TasksSearch' => ['status' => Tasks::STATUS_WAIT_PAYMENT]],
        ],
        [
            'label' => 'Отложенные' . " ({$count[Tasks::STATUS_DISABLED]})",
            'url' => ['ajax-index', 'TasksSearch' => ['status' => Tasks::STATUS_DISABLED]],
        ],
        [
            'label' => 'Удаленные' . " ({$count[Tasks::STATUS_DELETED]})",
            'url' => ['ajax-index', 'TasksSearch' => ['status' => Tasks::STATUS_DELETED]],
        ],
        [
            'label' => 'Проблемные' . " ({$count[Tasks::STATUS_WAIT]})",
            'url' => ['ajax-index', 'TasksSearch' => ['status' => Tasks::STATUS_WAIT]],
        ],
        [
            'label' => 'Оплаченные' . " ({$count[Tasks::STATUS_PAYMENT]})",
            'url' => ['ajax-index', 'TasksSearch' => ['status' => Tasks::STATUS_PAYMENT]],
        ],
    ],
    'options' => ['tag' => 'div'],
    'itemOptions' => ['tag' => 'div'],
    'headerOptions' => ['class' => 'my-class'],
    'clientOptions' => ['collapsible' => false],
]);
    ?>

</div>
