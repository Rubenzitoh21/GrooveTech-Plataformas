<?php

namespace backend\modules\api\controllers;

use Carbon\Carbon;
use common\models\Carrinhos;
use common\models\ProdutosCarrinhos;
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
        $cart->dtapedido = Carbon::now()->toDateTimeString();
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

    public function actionDeleteCartByUserid($id)
    {
        $authenticatedUser_id = Yii::$app->user->id;
        if ($authenticatedUser_id != $id) {
            $this->sendErrorResponse(403, 'Token não corresponde ao utilizador fornecido.');
        }

        $cart = Carrinhos::findOne([
            'user_id' => $id,
            'status' => "Ativo"
        ]);

        if (!$cart) {
            $this->sendErrorResponse(404, 'Nenhum carrinho ativo encontrado para este utilizador com o id: ' . $id);
        }


        $cartLines_id = ProdutosCarrinhos::findAll(['carrinhos_id' => $cart->id]);
        $cartLinesTotal = ProdutosCarrinhos::deleteAll(['carrinhos_id' => $cart->id]);


        if ($cart->delete()) {
            return [
                'message' => 'Carrinho e linhas do carrinho excluídos com sucesso.',
                'user_id' => $id,
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


    public function actionGetCartByUserid($id)
    {

        $authenticatedUser_id = Yii::$app->user->id;
        if ($authenticatedUser_id != $id) {
            $this->sendErrorResponse(403, 'Token não corresponde ao utilizador fornecido.');
        }

        $activeCart = Carrinhos::findOne([
            'user_id' => $id,
            'status' => "Ativo"
        ]);
        if (!$activeCart) {
            $this->sendErrorResponse(404, 'Nenhum carrinho ativo encontrado para este utilizador com o id: ' . $id);
        }
        $existingCartLines = ProdutosCarrinhos::findAll(['carrinhos_id' => $activeCart->id]);


        // Calculate the valor_iva of each cartline
        $iva = 0;
        foreach ($existingCartLines as $cartLine) {
            $iva += $cartLine->valor_iva * $cartLine->quantidade;
        }

        $subtotal = $activeCart->valortotal - $iva;
        return [
            'carrinho' => $activeCart,
            'total_iva' => $iva,
            'subtotal' => $subtotal
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