<?php
namespace frontend\controllers;

use frontend\models\ContactForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;

class SiteController extends Controller
{

    public function actionIndex()
    {
        $request = Yii::$app->request;

        return $this->render('index', [

        ]);
    }

}
