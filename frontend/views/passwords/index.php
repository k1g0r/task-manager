<?php

use common\models\Projects;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PasswordsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Passwords');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="passwords-index">

        <p>
            <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'project_id',
                    'label' => 'Projects',
                    'value' => function ($model) {
                        $r = null;
                        if (count($model->projects) > 0) {
                            foreach ($model->projects as $project) {
                                $r .= "$project->name<br>";
                            }
                        }
                        return $r;
                    },
                    'format' => 'raw',
                    'filter' => Projects::getMyProjectsNames()
                ],
                'label',
                'url:url',
                'login',
                'decs:ntext',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{copyPass} {view} {update} {delete}',
                    'buttons' => [
                        'copyPass' => function ($model, $key) {
                            $icon = Html::tag('span', '', ['class' => "fa fa-lock"]);

                            return Html::a(
                                $icon,
                                ['passwords/copy', 'id' => $key->id],
                                [
                                    'class' => 'copyPassword1',
                                    'onclick' => 'copyToClipboard(\'' . $key->password . '\');'
                                ]
                            );
                        }
                    ]
                ],
            ],
        ]); ?>


    </div>

<?php
$this->registerJs(<<<JS
$(document).on('click', '.copyPassword1', function (e) {
    e.preventDefault();
    var t = this;
    $(t).addClass("green");
    setTimeout(function () {
        $(t).removeClass("green");
    }, 1000);
});
$(document).on('click', '.copyPassword', function (e) {
    e.preventDefault();
    var t = this;
    var dataPass = $(t).attr('data-pass');
    if (dataPass != undefined) {
        // copyToClipboard(dataPass);
        // alert("Пароль скопирован в буфер \\n Переделай в форму с проверкой данных!");
        return false;
    }

    var url = $(this).attr('href');
    // Делаем запрос на пароль
    $.ajax({
        url: url,
        dataType: 'json',
        success: function (json) {
            if (json.error == '') {
                // copyToClipboard(json.pass);
                $(t).attr('data-pass', json.pass);
                $(t).attr('onclick', 'copyToClipboard(\'' + json.pass + '\')');
                $(t).click();
            } else {
                console.log(json.error);
                alert('Error!');
            }
        },
        error: function () {
            alert('Ошибка запроса!');
        }
    });
});
JS
);
