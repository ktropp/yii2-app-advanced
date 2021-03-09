<?php
return [
    'all' => [
        'class' => 'yii\web\AssetBundle',
        'basePath' => '@webroot/deploy',
        'baseUrl' => '@web/deploy',
        'css' => ['all.css'],
        'js' => [
            [
                'all.js',
                'defer' => 'defer',
            ]
        ],
    ],
    'yii\web\YiiAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
    'yii\widgets\ActiveFormAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
    'yii\web\JqueryAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
    'yii\bootstrap\BootstrapAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
    'frontend\assets\AppAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
    'kartik\select2\Select2Asset' => ['css' => [], 'js' => [], 'depends' => ['all']],
    'kartik\select2\Select2KrajeeAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
    'kartik\select2\ThemeKrajeeAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
    'kartik\base\WidgetAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
];