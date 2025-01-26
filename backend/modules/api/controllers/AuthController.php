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
            $this->sendErrorResponse(400, 'Username já existe');
        }

        if ($this->modelClass::findOne(['email' => $email])) {
            $this->sendErrorResponse(400, 'Email já existe');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {

            // Criar um novo utilizador
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

            // Atribuir o role de cliente ao utilizador
            $auth = Yii::$app->authManager;
            $userRole = $auth->getRole('cliente');
            $auth->assign($userRole, $user->id);

            // Criar um perfil para o utilizador
            $userProfile = new $this->userProfileModelClass;
            $userProfile->user_id = $user->id;
            $userProfile->dtaregisto = Carbon::now('Europe/Lisbon')->format('Y-m-d H:i:s');

            if (!$userProfile->save()) {
                Yii::error($userProfile->errors, 'debug');
                throw new ServerErrorHttpException("Erro ao salvar o perfil do utilizador.");
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
    public function actionRefreshToken()
    {
        $request = Yii::$app->request;

        // Get the current token from the request
        $currentToken = $request->post('current_token');
        if (empty($currentToken)) {
            throw new BadRequestHttpException("O token atual é obrigatório.");
        }

        // Find the user associated with the current token
        $user = $this->modelClass::findOne(['auth_key' => $currentToken]);
        if (!$user) {
            throw new UnauthorizedHttpException("Token inválido ou expirado.");
        }

        // Generate a new token for the user
        $user->generateAuthKey();

        // Save the new token in the database
        if (!$user->save()) {
            Yii::error($user->getErrors(), 'debug');
            throw new ServerErrorHttpException("Erro ao atualizar o token.");
        }

        return [
            'user_id' => $user->id,
            'new_token' => $user->auth_key,
        ];
    }


    private function sendErrorResponse($code, $message, $details = [])
    {
        Yii::$app->response->statusCode = $code;

        $responseData = [
            'error' => [
                'code' => $code,
                'message' => $message,
            ]
        ];

        if ($details) {
            $responseData['error']['details'] = $details;
        }

        Yii::$app->response->data = $responseData;
        Yii::$app->response->send();
        Yii::$app->end();
    }


}