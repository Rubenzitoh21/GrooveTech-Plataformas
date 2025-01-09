<?php

namespace backend\modules\api\controllers;

use DateTime;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class UserProfileController extends ActiveController
{

    public $modelClass = 'common\models\UserProfile';
    public $userModelClass = 'common\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
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
        $authenticatedUser_id = Yii::$app->user->id;
        if ($authenticatedUser_id != $id) {
            $this->sendErrorResponse(403, 'Token não corresponde ao utilizador fornecido.');
        }

        $user = $this->userModelClass::findOne($id);

        $userProfile = new $this->modelClass;
        $userProfileData = $userProfile::find()->where(['user_id' => $id])->one();

        if (!$userProfileData) {
            $this->sendErrorResponse(404, 'Profile not found.');
        }

        // Definir texto de placeholder para campos vazios
        $placeholderText = [
            'primeironome' => '',
            'apelido' => '',
            'codigopostal' => '',
            'localidade' => '',
            'rua' => '',
            'nif' => '',
            'dtanasc' => '',
            'telefone' => '',
            'genero' => ''
        ];

        foreach ($placeholderText as $field => $placeholder) {
            if (empty($userProfileData->$field)) {
                // atribui o valor do placeholder ao campo vazio
                $userProfileData->$field = $placeholder;
            }
        }

        return ['user' => $user, 'profile' => $userProfileData];
    }


    public function actionUpdateProfile($id)
    {
        $authenticatedUser_id = Yii::$app->user->id;
        if ($authenticatedUser_id != $id) {
            $this->sendErrorResponse(403, 'Token não corresponde ao utilizador fornecido.');
        }

        $userProfileData = $this->getUserProfile($id);
        if (!$userProfileData) {
            $this->sendErrorResponse(404, 'Perfil do utilizador não foi encontrado.', [
                'user_id' => $id
            ]);
        }

        $params = Yii::$app->request->getBodyParams();
        $this->updateUserProfile($params, $userProfileData);

        return [
            'message' => 'Perfil atualizado com sucesso.',
            'profile' => $userProfileData
        ];
    }

    private function updateUserProfile($params, $userProfileData)
    {
        // Define os campos válidos que podem ser atualizados
        $validFields = [
            'primeironome', 'apelido', 'telefone', 'nif', 'rua', 'localidade', 'codigopostal', 'genero', 'dtanasc'
        ];

        $invalidFields = [];

        //Valida os campos recebidos, se não encontrar campos válidos, adiciona-os à lista de campos inválidos
        foreach ($params as $field => $value) {
            if (!in_array($field, $validFields)) {
                $invalidFields[] = $field;
            }
        }

        // Se existirem campos inválidos, envia uma resposta de erro e mostra os campos inválidos junta com os campos válidos
        if (!empty($invalidFields)) {
            $this->sendErrorResponse(400, 'A request não pode ser executada porque tem alguns campos inválidos', [
                'invalid_fields' => $invalidFields,
                'valid_fields' => [
                    'primeironome', 'apelido', 'telefone', 'nif', 'rua', 'localidade', 'codigopostal', 'genero', 'dtanasc'
                ]
            ]);
        }

        foreach ($validFields as $field) {
            if (isset($params[$field])) {

                // Validação da data de nascimento
                if ($field == 'dtanasc') {
                    $date = DateTime::createFromFormat('d-m-Y', $params[$field]);
                    if ($date && $date->format('d-m-Y') === $params[$field]) {
                        $params[$field] = $date->format('Y-m-d');
                    } else {
                        $this->sendErrorResponse(400,
                            'Data de nascimento inválida. Por favor, insira a data no formato dd-mm-aaaa (ex: 01-01-2000).');
                    }
                }
                //  Validação do género verifica se o campo na request é "genero" e obriga que o valor seja M ou F
                if ($field == 'genero' && !in_array($params[$field], ['M', 'F', ''])) {
                    $this->sendErrorResponse(400, 'Género inválido. Por favor, insira M, F ou deixe em branco.');
                }
                // Validação do NIF verifica se o campo na request é "nif" e obriga que o valor tenha 9 dígitos
                if ($field == 'nif' && !empty($params[$field]) && !preg_match('/^\d{9}$/', $params[$field])) {
                    $this->sendErrorResponse(400, 'NIF inválido. O NIF deve conter exatamente 9 dígitos.');
                }
                // Verifica se o numero de telefone já está em uso por outro utilizador
                $userHasPhone = $this->modelClass::find()
                    ->where(['telefone' => $params['telefone']])
                    ->andWhere(['!=', 'user_id', $userProfileData->user_id]) // Exclui o utilizador atual da pesquisa
                    ->one();
                if ($userHasPhone) {
                    $this->sendErrorResponse(400, 'O telefone fornecido já está em uso por outro utilizador.');
                }
                // Verifica se o NIF já está em uso por outro utilizador
                $userHasNif = $this->modelClass::find()
                    ->where(['nif' => $params['nif']])
                    ->andWhere(['!=', 'user_id', $userProfileData->user_id]) // Exclui o utilizador atual da pesquisa
                    ->one();

                if ($userHasNif) {
                    $this->sendErrorResponse(400, 'O NIF fornecido já está em uso por outro utilizador.');
                }
                $userProfileData->$field = $params[$field];
            }
        }

        if ($userProfileData->save()) {
            return $userProfileData;
        }

        return null;
    }


    private function getUserProfile($userId)
    {
        return $this->modelClass::find()->where(['user_id' => $userId])->one();
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