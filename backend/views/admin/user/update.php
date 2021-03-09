<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use mdm\admin\components\Helper;
use app\components\MyHelpers;
use unclead\multipleinput\MultipleInput;
use common\models\Region;
use common\models\Category;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Administrátoři'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$controllerId = $this->context->uniqueId . '/';
?>
<div class="user-update <?= $model->role; ?>" data-type="<?= $model->role; ?>">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if ($model->status == 0 && Helper::checkRoute($controllerId . 'activate')) {
            echo Html::a(Yii::t('rbac-admin', 'Activate'), ['activate', 'id' => $model->id], [
                'class' => 'btn btn-primary',
                'data' => [
                    'confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
        <?php
        if (Helper::checkRoute($controllerId . 'delete')) {
            echo Html::a(Yii::t('rbac-admin', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <?= $form->field($model, 'username')->textInput(['readonly' => true]) ?>
        <?= $form->field($model, 'email') ?>
        <?php
        $roles = User::$roles;
        if(!User::hasRole("superadmin", Yii::$app->user->id)){
            unset($roles['superadmin']);
        }
        ?>
        <?php if(User::hasRole("superadmin", Yii::$app->user->id) || User::hasRole("admin", Yii::$app->user->id)): ?>
            <?= $form->field($model, 'role')->dropDownList($roles); ?>
        <?php endif; ?>
        <div class="form-group">
            <?= Html::submitButton('Uložit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>

</div>
