<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\UserProfile;
use Carbon\Carbon;
use yii\web\NotFoundHttpException;


class UserForm extends Model

{
    public $id;
    public $username;
    public $email;
    public $password;
    public $role;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //rules user
            ['username', 'trim'],
            ['username', 'required' , 'message' => 'Este campo não pode ficar em branco.'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este nome de utilizador já está a ser utilizado.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required', 'message' => 'Este campo não pode ficar em branco.'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este email já está a ser utilizado.'],

            ['password', 'required', 'message' => 'Este campo não pode ficar em branco.'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['role', 'required', 'message' => 'Este campo não pode ficar em branco.'],
            ['role', 'string', 'max' => 255],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function createUser()
    {
        if ($this->validate()) {

            $user = new User();

            $user->username = $this->username;
            $user->email = $this->email;
            $user->created_at = Carbon::now();
            $user->updated_at = Carbon::now();
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();

            $user->save();

            $this->id = $user->id;


            $auth = \Yii::$app->authManager;
            $userRole = $auth->getRole($this->role);
            $auth->assign($userRole, $user->id);

            return $this->sendEmail($user);
        }
        return null;
    }

    public function updateUser()
    {
        $user = User::findOne(['id' => $this->id]);

        $user->username = $this->username;

        $user->email = $this->email;

        $user->save();

        $auth = Yii::$app->authManager;
        $userRole = $auth->getRole($this->role);
        $auth->revokeAll($user->id);
        $auth->assign($userRole, $user->id);

        return true;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }

    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}