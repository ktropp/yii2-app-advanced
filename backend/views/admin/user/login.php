<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Login */

$this->title = Yii::t('rbac-admin', 'Přihlášení');
$this->params['breadcrumbs'] = [$this->title];
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Vyplňte prosím tyto pole pro přihlášení:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'password')->passwordInput(); ?>
            <?= $form->field($model, 'rememberMe')->checkbox(); ?>
            <div style="color:#999;margin:1em 0">
                Pokud jste zapomněli heslo můžete si ho zde <?= Html::a('vyresetovat', ['user/request-password-reset']) ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('rbac-admin', 'Přihlásit se'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
