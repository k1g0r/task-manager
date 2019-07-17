<?php

/* @var $this yii\web\View */
/* @var $model common\models\Tasks */

$this->title = Yii::t('app', 'Update: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tasks-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
