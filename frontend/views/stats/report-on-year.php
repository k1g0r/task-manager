<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Отчет по оплатам: ';

// Собираем данные в массив перед выводом
$data = [];

Pjax::begin(['id' => 'report-on-year', 'enablePushState' => false]);

// формируем форму для фильтарциии отчеты (клиент, статус, поля для вывода)
echo Html::beginForm(['stats/report-on-year'], 'get', ['data-pjax' => 'report-on-year']);

echo Html::dropDownList('year', '', ['2019', '2020'], ['class' => 'form-control']);

echo Html::submitButton('Отправить', ['class' => 'btn btn-block btn-info']);

echo Html::endForm();

echo dosamigos\chartjs\ChartJs::widget([
    'type' => 'bar',
    'id' => 'structurePie',
    'options' => [
        'height' => 200,
        'width' => 400,
    ],
    'data' => [
        'labels' => [
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь'
        ], // Your labels
        'datasets' => [
            [
                'data' => $wait, // Your dataset
                'label' => 'Ожидание',
            ],
            [
                'data' => $payment, // Your dataset
                'label' => 'Выплачено',
                'backgroundColor' => '#00a65a',
                'bodySpacing' => 10000,
            ],
        ]
    ],
    'clientOptions' => [
        'legend' => [
            'display' => true,
            'position' => 'bottom',
            'labels' => [
                'fontSize' => 14,
                'fontColor' => "#425062",
            ]
        ],
        'tooltips' => [
            'enabled' => true,
            'intersect' => true
        ],
        'hover' => [
            'mode' => false
        ],
        'layout' => [
            'padding' => [
                'top' => 30
            ],
        ],
        'maintainAspectRatio' => false,
        'barShowStroke' => true,
    ],
    'plugins' =>
        new \yii\web\JsExpression("
        [{
            afterDatasetsDraw: function(chart, easing) {
                var ctx = chart.ctx;
                chart.data.datasets.forEach(function (dataset, i) {
                    var meta = chart.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            // Draw the text in black, with the specified font
                            ctx.fillStyle = 'rgb(0, 0, 0)';

                            var fontSize = 16;
                            var fontStyle = 'normal';
                            var fontFamily = 'Helvetica';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                            // Just naively convert to string for now
                            var dataString = '';dataset.data[index].toString()+' руб.';

                            // Make sure alignment settings are correct
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            var padding = 5;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                        });
                    }
                });
            }
        }]")
]);

Pjax::end();
