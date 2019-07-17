<?php

use yii\grid\GridView;
use yii\helpers\Html;
use common\behaviors\StatusBehavior;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ClientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Clients');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'phone',
            'otherContact:ntext',
            'note',
            [
                'attribute' => 'status',
                'value' => 'statusTitle',
                'filter' => $searchModel->getStatuses(),
            ],
            [
                'label' => 'Проекты (Активные/Всего)',
                'value' => function ($model) {
                    return Html::a(
                        $model->getProjects()->andWhere(['status' => StatusBehavior::STATUS_ACTIVE])->count()
                        . ' / ' .
                        $model->getProjects()->count(),
                        [
                            '/projects',
                            'ProjectsSearch' => [
                                'status' => StatusBehavior::STATUS_ACTIVE,
                                'client_id' => $model->id
                            ]
                        ]
                    );
                },
                'format' => 'raw'
            ],
            [
                'label' => 'Выплаты',
                'value' => function ($model) {
                    $info = $model->totalPriceInfo();
                    $diffTotalHtml = $info['diffTotal'] > 0 ? '+' . $info['diffTotal'] : $info['diffTotal'];
                    $class = $info['diffTotal'] < 0 ? 'font-red' : 'font-green';
                    return "Всего выплачено: {$info['total']} руб.<br>
                            Переплата: <span class='$class'>$diffTotalHtml  руб.</span><br>
                            В процессе: {$info['wait']}  руб.";
                },
                'format' => 'raw'
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
