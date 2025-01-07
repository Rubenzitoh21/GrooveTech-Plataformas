<?php

namespace backend\modules\api\controllers;

use common\models\Carrinhos;
use common\models\ProdutosCarrinhos;
use common\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class CarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\Carrinhos';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    public function actionCreateCart()
    {
        $authenticatedUser_id = Yii::$app->user->id;

        $user_id = Yii::$app->request->post('user_id');

        if ($authenticatedUser_id != $user_id) {
            $this->sendErrorResponse(403, 'Token não corresponde ao utilizador fornecido.');
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
                'message' => 'Carrinho Criado com Sucesso',
                'carrinho' => $cart
            ];
        }
        return [
            'message' => 'Erro ao criar o carrinho',
            'errors' => $cart->errors
        ];

    }

    public function actionDeleteCartByUserid($user_id)
    {
        // Get the access token from the GET request
        $accessToken = Yii::$app->request->get('access-token');
        if (!$accessToken) {
            $this->sendErrorResponse(400, 'Parâmetro obrigatório em falta: access-token');
        }

        $cart = Carrinhos::findOne([
            'user_id' => $user_id,
            'status' => "Ativo"
        ]);

        if (!$cart) {
            $this->sendErrorResponse(404, 'Nenhum carrinho ativo encontrado para este utilizador com o id: ' . $user_id);
        }


        $cartLines_id = ProdutosCarrinhos::findAll(['carrinhos_id' => $cart->id]);
        $cartLinesTotal = ProdutosCarrinhos::deleteAll(['carrinhos_id' => $cart->id]);


        if ($cart->delete()) {
            return [
                'message' => 'Carrinho e linhas do carrinho excluídos com sucesso.',
                'user_id' => $user_id,
                'carrinho_id_excluido' => $cart->id,
                'linhas_carrinho' => $cartLines_id,
                'linhas_carrinho_excluidas' => $cartLinesTotal

            ];
        }

        return [
            'message' => 'Erro ao deletar o carrinho.',
            'errors' => $cart->errors
        ];
    }


    public function actionGetCartByUserid($user_id)
    {
        $accessToken = Yii::$app->request->get('access-token');
        if (!$accessToken) {
            $this->sendErrorResponse(400, 'Parâmetro obrigatório em falta: access-token');
        }

        $this->getUserByIdAndToken($user_id, $accessToken);
        $activeCart = Carrinhos::findOne([
            'user_id' => $user_id,
            'status' => "Ativo"
        ]);
        if (!$activeCart) {
            $this->sendErrorResponse(404, 'Nenhum carrinho ativo encontrado para este utilizador com o id: ' . $user_id);
        }

        return $activeCart;
    }

    public function getUserByIdAndToken($id, $accessToken)
    {
        $userModel = new User();
        return $userModel::find()->where(['id' => $id, 'auth_key' => $accessToken])->one();
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