<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mdm\admin\models\AuthItem;
use yii\helpers\ArrayHelper;
use common\models\User;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Signup */


$this->title = Yii::t('rbac-admin', 'Vytvoření administrátora');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::errorSummary($model)?>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'retypePassword')->passwordInput() ?>
            <?php
            $roles = User::$roles;

            if(!User::hasRole("superadmin", Yii::$app->user->id)){
                //unset($roles['superadmin']);
            }
            ?>
            <?= $form->field($model, 'role')->dropDownList($roles); ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('rbac-admin', 'Vytvořit administrátora'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
