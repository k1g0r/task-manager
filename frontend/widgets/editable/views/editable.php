<?php

use yii\helpers\Html;
use frontend\widgets\editable\AppAsset\AppAsset;

AppAsset::register($this);
// уникальный ид для поля
$id = 'editable' . time() . '_' . rand(0, 9999);

echo Html::a($value, '#', array_merge(['id' => $id], $options));

$json = json_encode($data);
$this->registerJS(<<<JS
    $(document).ready(function() {
        var config = JSON.parse('$json');
        $('#$id').editable(config);
    });
JS
);
