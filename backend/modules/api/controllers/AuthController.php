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
            Yii::error("Username e password são obrigatórios.", 'debug');
            throw new BadRequestHttpException("Username e password são obrigatórios.");
        }

        $user = $this->modelClass::findOne(['username' => $username]);

        if (!$user) {
            Yii::error("Utilizador não encontrado: $username", 'debug');
            throw new NotFoundHttpException("Utilizador não encontrado.");
        }

        if (!$user->validatePassword($password)) {
            Yii::error("Password inválida para o utilizador: $username", 'debug');
            throw new UnauthorizedHttpException("Password inválida.");
        }

        // Generate and return an authentication token
        $user->generateAuthKey();


        if (!$user->save()) {
            Yii::error($user->getErrors(), 'debug');
            throw new ServerErrorHttpException("Erro ao gerar o token de acesso.");
        }

        return [
            'user' => $user,
        ];
    }


}