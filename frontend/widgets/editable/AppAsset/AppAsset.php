<?php

namespace frontend\widgets\editable\AppAsset;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@frontend/widgets/editable/web';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap-editable.css'
    ];
    public $js = [
        'js/bootstrap-editable.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
