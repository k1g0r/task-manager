<?php

/* @var $this yii\web\View */
/* @var $model common\models\Passwords */

$this->title = Yii::t('app', 'Update Passwords: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Passwords'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="passwords-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
