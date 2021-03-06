<?php

use common\models\Projects;
use common\models\Tasks;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Tasks */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs(<<<JS
    $('.chosen').chosen({width: '100%', allow_single_deselect: true});
JS
);

?>

<div class="tasks-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'project_id')->dropDownList(Projects::getMyProjectsNames(['name' => SORT_ASC]), [
                'class' => 'chosen'
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::merge(["" => ""],
                Tasks::getMyTasksNames()), [
                'class' => 'chosen'
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList($model->statuses) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'hoursPrice')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'time')->textInput() ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'total')->textInput() ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'deadline')->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => [
                        'class' => 'form-control'
                ]
            ]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'taskText')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'resultText')->textarea(['rows' => 6]) ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', 'Passwords'),
                    ['passwords/index', 'PasswordsSearch' => ['project_id' => $model->project_id]],
                    [
                        'data-toggle' => 'modal',
                        'data-target' => '#modalBoxAjax',
                        'class' => 'btn btn-primary'
                    ]) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(<<<JS
    $(document).on('change', '#tasks-hoursprice, #tasks-time', function() {
      var hourPrice = $('#tasks-hoursprice').val();
      var time = $('#tasks-time').val();
      
      $('#tasks-total').val(Math.floor(hourPrice * (time / 60)));
    });

JS
);
