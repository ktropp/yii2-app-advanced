<?php

namespace backend\controllers;

use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;
use backend\models\LoginForm;
use mdm\admin\components\UserStatus;
use mdm\admin\controllers\UserController;
use mdm\admin\models\form\ChangePassword;
use mdm\admin\models\form\Login;
use mdm\admin\models\form\PasswordResetRequest;
use mdm\admin\models\form\ResetPassword;
use backend\models\UserSearch;
use common\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\base\UserException;
use yii\filters\VerbFilter;
use yii\mail\BaseMailer;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AdminUserController extends UserController
{
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $auth = Yii::$app->authManager;

        $model = $this->findModel($id);
        if(!$model){
            throw new NotFoundHttpException();
        }

        if($model->load(Yii::$app->request->post())){
            if($model->save()){
                if(Yii::$app->user->can('change_user_role')){
                    if($model->role != $model->getRole()){
                        //change role
                        $role_model = $auth->getRole($model->role);
                        $auth->revokeAll($model->id);
                        $auth->assign($role_model, $model->id);
                    }
                }

                Yii::$app->getSession()->setFlash('success', 'Uživatel úspěšně uložen.');
            }else{
                Yii::$app->getSession()->setFlash('error', 'Uživatele se nepodařilo uložit.');
            }




        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($user = $model->signup()) {
                Yii::$app->getSession()->setFlash('success', 'Uživatel byl úspěšně přidán.');
                return $this->redirect(['update', 'id' => $user->getId()]);
            }else{
                Yii::$app->getSession()->setFlash('error', 'Uživatele se nepodařilo přidat.');
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->getUser()->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Na email vám byly zaslány další instrukce.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Omlouváme se, ale nepodařilo se nám vyresetovat heslo pro tento email.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Reset password
     * @return string
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'Nové heslo bylo uloženo.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
