<?php

use common\models\Projects;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Passwords */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs(<<<JS
    $('.chosen').chosen({width: '100%'});
JS
);

?>

<div class="passwords-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'projectIds')->dropDownList(Projects::getMyProjectsNames(), [
        'multiple' => true,
        'class' => 'chosen'
    ]) ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'decs')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
