<?php
namespace common\components;

use common\models\Language;
use Yii;

class Slugify extends \yii\base\Model{

    static function text($text){
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        $text = @iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = trim($text, '-');

        $text = preg_replace('~-+~', '-', $text);

        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}