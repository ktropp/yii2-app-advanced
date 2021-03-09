<?php
namespace backend\models;

use mdm\admin\components\UserStatus;
use mdm\admin\models\User;
use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $class = Yii::$app->getUser()->identityClass ? : 'mdm\admin\models\User';
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => $class,
                'filter' => ['status' => UserStatus::ACTIVE],
                'message' => 'Uživatel s tímto emailem neexistuje.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $class = Yii::$app->getUser()->identityClass ? : 'mdm\admin\models\User';
        $user = $class::findOne([
            'status' => UserStatus::ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!ResetPasswordForm::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            }

            if ($user->save()) {
                return Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject('Reset hesla - ' . Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }
}
