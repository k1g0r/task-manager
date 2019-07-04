<?php

use yii\grid\GridView;
use yii\helpers\Html;

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
                'label' => 'Проекты',
                'value' => function ($model) {
                    return '0';
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
