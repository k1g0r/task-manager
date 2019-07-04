<?php

/* @var $this yii\web\View */
/* @var $model common\models\Clients */

$this->title = Yii::t('app', 'Update Clients: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="clients-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
