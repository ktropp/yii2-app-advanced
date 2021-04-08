<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class FontAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&family=Open+Sans&display=swap',
    ];
    public $js = [];
    public $depends = [];
}