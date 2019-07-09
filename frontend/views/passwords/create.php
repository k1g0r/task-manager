<?php

/* @var $this yii\web\View */
/* @var $model common\models\Passwords */

$this->title = Yii::t('app', 'Create Passwords');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Passwords'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="passwords-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
