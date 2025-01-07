<?php

namespace backend\modules\api\controllers;

use common\models\Carrinhos;
use Yii;
use yii\rest\ActiveController;

class CarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\Carrinhos';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreateCart()
    {
        $accessToken = Yii::$app->request->get('access-token');
        if (!$accessToken) {
            $this->sendErrorResponse(400, 'Parâmetro obrigatório em falta: access-token');
        }

        //get the use_id from the POST request
        $user_id = Yii::$app->request->post('user_id');
        if (!$user_id) {
            $this->sendErrorResponse(400, 'campo obrigatório em falta: user_id');
        }

        // Check if the user has an active cart
        $existingCart = Carrinhos::findOne([
            'user_id' => $user_id,
            'status' => "Ativo"
        ]);
        if ($existingCart) {
            $this->sendErrorResponse(400, 'Já existe um carrinho ativo para este utilizador', [
                'carrinho' => $existingCart
            ]);
        }
        // Create a new cart
        $cart = new Carrinhos();
        $cart->user_id = $user_id;
        $cart->valortotal = 0;
        $cart->status = "Ativo";

        if ($cart->save()) {
            return [
                'message' => 'Cart Criado com Sucesso',
                'carrinho' => $cart
            ];
        }
        return [
            'message' => 'Erro ao criar o carrinho',
            'errors' => $cart->errors
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