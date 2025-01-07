<?php

namespace backend\modules\api\controllers;

use common\models\Carrinhos;
use common\models\ProdutosCarrinhos;
use Yii;
use yii\rest\ActiveController;
use yii\web\Response;

class ProdutosCarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\ProdutosCarrinhos';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreateProdutosCarrinho()
    {
        // Retrieve parameters and check if they exist
        $accessToken = Yii::$app->request->get('access-token');
        $this->checkRequiredParam($accessToken, 'access-token');

        $produtos_id = Yii::$app->request->post('produtos_id');
        $this->checkRequiredParam($produtos_id, 'produtos_id');

        $quantidade = Yii::$app->request->post('quantidade');
        $this->checkRequiredParam($quantidade, 'quantidade');

        $preco_venda = Yii::$app->request->post('preco_venda');
        $this->checkRequiredParam($preco_venda, 'preco_venda');

        $valor_iva = Yii::$app->request->post('valor_iva');
        $this->checkRequiredParam($valor_iva, 'valor_iva');

        $subtotal = Yii::$app->request->post('subtotal');
        $this->checkRequiredParam($subtotal, 'subtotal');

        $user_id = Yii::$app->request->post('user_id');
        $this->checkRequiredParam($user_id, 'user_id');

        $user = $this->getUserByIdAndToken($user_id, $accessToken);
        if (!$user) {
            $this->sendErrorResponse(404, 'Utilizador não encontrado com o ID ' . $user_id . ' e o token de acesso ' . $accessToken);
        }
        $existingCart = Carrinhos::find()->where(['user_id' => $user_id, 'status' => "Ativo"])
            ->orderBy(['dtapedido' => SORT_DESC])->one();

        if (!$existingCart) {
            $this->sendErrorResponse(404, 'Carrinho não encontrado para o utilizador com o ID ' . $user_id);
        }
        $produtosCarrinho = new ProdutosCarrinhos();
        $produtosCarrinho->quantidade = $quantidade;
        $produtosCarrinho->preco_venda = $preco_venda;
        $produtosCarrinho->valor_iva = $valor_iva;
        $produtosCarrinho->subtotal = $subtotal;
        $produtosCarrinho->produtos_id = $produtos_id;
        $produtosCarrinho->carrinhos_id = $existingCart->id;

        if ($produtosCarrinho->save()) {
            return $this->generateSuccessResponse($produtosCarrinho);
        } else {
            $this->sendErrorResponse(500, 'Erro ao criar o produto no carrinho.', $produtosCarrinho->getErrors());
        }
    }
    private function checkRequiredParam($param, $paramName)
    {
        if (!$param) {
            $this->sendErrorResponse(400, "Parâmetro obrigatório em falta: {$paramName}");
        }
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

    private function generateSuccessResponse(ProdutosCarrinhos $produtosCarrinho)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = [
            'message' => 'Linha do carrinho criada com sucesso.',
            'ProdutosCarrinhos' => $this->mapProdutosCarrinhosResponse($produtosCarrinho)
        ];
        return Yii::$app->response;
    }

    private function mapProdutosCarrinhosResponse(ProdutosCarrinhos $produtosCarrinho)
    {
        return [
            'id' => (string)$produtosCarrinho->id,
            'quantidade' => (string)$produtosCarrinho->quantidade,
            'preco_venda' => (string)$produtosCarrinho->preco_venda,
            'valor_iva' => (string)$produtosCarrinho->valor_iva,
            'subtotal' => (string)$produtosCarrinho->subtotal,
            'produtos_id' => (string)$produtosCarrinho->produtos_id,
            'carrinhos_id' => (string)$produtosCarrinho->carrinhos_id
        ];
    }

    private function getUserByIdAndToken($id, $accessToken)
    {
        $userModel = new \common\models\User();
        return $userModel::find()->where(['id' => $id, 'auth_key' => $accessToken])->one();
    }
}
