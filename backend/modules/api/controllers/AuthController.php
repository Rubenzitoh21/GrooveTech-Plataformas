<?php

namespace backend\modules\api\controllers;

use Carbon\Carbon;
use Exception;
use Yii;
use yii\rest\ActiveController;
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

    public function actionSignup()
    {
        $request = Yii::$app->request;
        $username = $request->post('username');
        $password = $request->post('password');
        $email = $request->post('email');

        if ($this->modelClass::findOne(['username' => $username])) {
            throw new BadRequestHttpException("Utilizador já existe");
        }

        if ($this->modelClass::findOne(['email' => $email])) {
            throw new BadRequestHttpException("Email já existe");
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {

            // Create user
            $user = new $this->modelClass;
            $user->username = $username;
            $user->email = $email;
            $user->updated_at = Carbon::now();
            $user->created_at = Carbon::now();
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();

            if (!$user->save()) {
                Yii::error($user->errors, 'debug');
                throw new ServerErrorHttpException("Erro ao salvar o utilizador.");
            }

            // Assign role
            $auth = Yii::$app->authManager;
            $userRole = $auth->getRole('cliente');
            $auth->assign($userRole, $user->id);

            // Create user profile
            $userProfile = new $this->userProfileModelClass;
            $userProfile->user_id = $user->id;
            $userProfile->dtaregisto = Carbon::now('Europe/Lisbon')->format('Y-m-d H:i:s');

            if (!$userProfile->save()) {
                Yii::error($userProfile->errors, 'debug');
                throw new ServerErrorHttpException("Erro ao salvar o perfil do utilizador.");
            }

            // Create initial cart
            $carrinhoNovo = new $this->carrinhosModelClass;
            $carrinhoNovo->user_id = $user->id;
            $carrinhoNovo->dtapedido = Carbon::now('Europe/Lisbon')->format('Y-m-d H:i:s');
            $carrinhoNovo->status = 'Ativo';
            $carrinhoNovo->valortotal = 0;

            if (!$carrinhoNovo->save()) {
                throw new ServerErrorHttpException("Erro ao salvar o carrinho.");
            }

            $transaction->commit();
            return [
                'user' => $user,
            ];
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }


}