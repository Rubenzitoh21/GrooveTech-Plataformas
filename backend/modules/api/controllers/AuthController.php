<?php

namespace backend\modules\api\controllers;

use Exception;
use Yii;
use yii\rest\ActiveController;
use Carbon\Carbon;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;


class AuthController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public $carrinhosModelClass = 'common\models\Carrinhos';

    public $userProfileModelClass = 'common\models\UserProfile';

    public function actionLogin()
    {
        $request = Yii::$app->request;
        $username = $request->post('username');
        $password = $request->post('password');

        if (empty($username) || empty($password)) {
            throw new BadRequestHttpException("Username e password são obrigatórios.");
        }

        $user = $this->modelClass::findOne(['username' => $username]);

        if (!$user) {
            throw new NotFoundHttpException("Utilizador não encontrado.");
        }

        if (!$user->validatePassword($password)) {
            throw new UnauthorizedHttpException("Password inválida.");
        }

        //generate and return an authentication token
            $user->generateAuthKey();
            if (!$user->save()) {
                throw new ServerErrorHttpException("Erro ao gerar o token de acesso.");
            }
            return [
                'user' => $user,
                'access_token' => $user->access_token,
            ];
    }



}