<?php

namespace backend\modules\api\controllers;

use DateTime;
use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class UserProfileController extends ActiveController
{

    public $modelClass = 'common\models\UserProfile';
    public $userModelClass = 'common\models\User';

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Get profile data for a user based on ID and access token
     * @param $id
     * @param $accessToken
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetProfile($id)
    {
        $accessToken = Yii::$app->request->get('access-token');

        if (!$accessToken) {
            throw new \yii\web\BadRequestHttpException("Falta o parâmetro: access-token");
        }


        $userModel = new $this->userModelClass;
        $user = $userModel::find()->where(['id' => $id, 'auth_key' => $accessToken])->one();

        if ($user == null) {
            throw new NotFoundHttpException("Utilizador não encontrado com o ID " . $id . " e o token de acesso " . $accessToken);
        }

        $userProfile = new $this->modelClass;
        $userProfileData = $userProfile::find()->where(['user_id' => $user->id])->one();

        return ['user' => $user, 'profile' => $userProfileData];
    }

    public function actionUpdateProfile($id)
    {
        $accessToken = Yii::$app->request->get('access-token');
        if (!$accessToken) {
            $this->sendErrorResponse(400, 'Parâmetro obrigatório em falta: access-token');
        }

        $user = $this->getUserByIdAndToken($id, $accessToken);
        if (!$user) {
            $this->sendErrorResponse(404, 'Utilizador não encontrado com o ID ' . $id . ' e o token de acesso ' . $accessToken);
        }

        $userProfileData = $this->getUserProfile($user->id);
        if (!$userProfileData) {
            $this->sendErrorResponse(404, 'Perfil do utilizador não foi encontrado.', [
                'user_id' => $user->id
            ]);
        }

        $params = Yii::$app->request->getBodyParams();
        $updatedFields = $this->updateUserProfile($params, $userProfileData);

        if ($updatedFields) {
            $updatedFieldsWithValues = [];
            foreach ($updatedFields as $field) {
                $updatedFieldsWithValues[] = $field . ': ' . $userProfileData->$field;
            }
            $this->sendErrorResponse(200, 'Perfil atualizado com sucesso.', [
                'updated_fields' => $updatedFieldsWithValues,
                'profileOnUpdate' => $userProfileData
            ]);
        }
    }

    private function updateUserProfile($params, $userProfileData)
    {
        // Define os campos válidos que podem ser atualizados
        $validFields = [
            'primeironome', 'apelido', 'telefone', 'nif', 'rua', 'localidade', 'codigopostal', 'genero', 'dtanasc'
        ];

        $updatedFields = [];
        $invalidFields = [];

        //Valida os campos recebidos, se não encontrar campos válidos, adiciona-os à lista de campos inválidos
        foreach ($params as $field => $value) {
            if (!in_array($field, $validFields)) {
                $invalidFields[] = $field;
            }
        }

        if (!empty($invalidFields)) {
            $this->sendErrorResponse(400, 'A request não pode ser executada porque tem alguns campos inválidos', [
                'invalid_fields' => $invalidFields,
                'valid_fields' => [
                    'primeironome', 'apelido', 'telefone', 'nif', 'rua', 'localidade', 'codigopostal', 'genero', 'dtanasc'
                ]
            ]);
        }

        foreach ($validFields as $field) {
            if (isset($params[$field]) && !empty($params[$field])) {

                // Validação da data de nascimento
                if ($field == 'dtanasc') {
                    $date = DateTime::createFromFormat('d-m-Y', $params[$field]);
                    if ($date && $date->format('d-m-Y') === $params[$field]) {
                        $params[$field] = $date->format('Y-m-d');
                    } else {
                        $this->sendErrorResponse(400, 'Data de nascimento inválida. Por favor, insira a data no formato dd-mm-aaaa');
                    }
                }
                //  Validação do género verifica se o campo na request é "genero" e obriga que o valor seja M ou F
                if ($field == 'genero' && !in_array($params[$field], ['M', 'F'])) {
                    $this->sendErrorResponse(400, 'Género inválido. Por favor, insira M, F ou O');
                }
                $userProfileData->$field = $params[$field];
                $updatedFields[] = $field;
            }
        }

        if ($updatedFields && $userProfileData->save()) {
            return $updatedFields;
        }

        return null;
    }


    private function getUserProfile($userId)
    {
        return $this->modelClass::find()->where(['user_id' => $userId])->one();
    }

    private function getUserByIdAndToken($id, $accessToken)
    {
        return $this->userModelClass::find()->where(['id' => $id, 'auth_key' => $accessToken])->one();
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