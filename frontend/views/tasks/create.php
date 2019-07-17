<?php

/* @var $this yii\web\View */
/* @var $model common\models\Tasks */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
