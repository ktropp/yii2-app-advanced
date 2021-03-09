<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use \kartik\grid\ActionColumn;
use mdm\admin\components\Helper;
use common\models\User;
use app\components\MyHelpers;

/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Administrátoři';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'heading' => $this->title,
            'before' => '',
        ],
        'toolbar'=>[
            [
                'content' => '<a class="btn btn-primary" href="' . Url::to(['user/signup']) . '">Přidat uživatele</a>'
            ],
            '{export}',
        ],
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => Helper::filterActionColumn(['update', 'activate', 'delete']),
                'buttons' => [
                    'activate' => function($url, $model) {
                        if ($model->status == 10) {
                            return '';
                        }
                        $options = [
                            'title' => Yii::t('rbac-admin', 'Aktivovat'),
                            'aria-label' => Yii::t('rbac-admin', 'Aktivovat'),
                            'data-confirm' => Yii::t('rbac-admin', 'Opravdu chcete toho uživatele aktivovat?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                    }
                ]
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'role',
                'value' => function($model){
                    $roles = User::$roles;

                    if($model->role)
                        return $roles[$model->role];
                },
                'filter' => User::$roles,
            ],
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status == 0 ? 'Neaktivní' : 'Aktivní';
                },
                'filter' => [
                    0 => 'Neaktivní',
                    10 => 'Aktivní'
                ]
            ],
        ],
    ]);
    ?>
</div>
