<?php

/* @var $this yii\web\View */
/* @var $model common\models\Clients */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
