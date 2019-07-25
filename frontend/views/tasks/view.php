<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Tasks */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tasks-view">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', 'Passwords'),
            ['passwords/index', 'PasswordsSearch' => ['project_id' => $model->project_id]],
            [
                'data-toggle' => 'modal',
                'data-target' => '#modalBoxAjax',
                'class' => 'btn btn-primary'
            ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'project_id',
            'parent_id',
            'name',
            'taskText:ntext',
            'resultText:ntext',
            'hoursPrice',
            'time:datetime',
            'waiting_at:datetime',
            'payment_at:datetime',
            'total',
            'status',
        ],
    ]) ?>

</div>
