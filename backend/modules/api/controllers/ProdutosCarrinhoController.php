<?php

namespace backend\modules\api\controllers;

use common\models\Carrinhos;
use common\models\ProdutosCarrinhos;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class ProdutosCarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\ProdutosCarrinhos';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    public function actionCreateCartLines()
    {
        $produtos_id = Yii::$app->request->post('produtos_id');
        $this->checkRequiredParam($produtos_id, 'produtos_id');

        $quantidade = Yii::$app->request->post('quantidade');
        $this->checkRequiredParam($quantidade, 'quantidade');

        $preco_venda = Yii::$app->request->post('preco_venda');
        $this->checkRequiredParam($preco_venda, 'preco_venda');

        $valor_iva = Yii::$app->request->post('valor_iva');
        $this->checkRequiredParam($valor_iva, 'valor_iva');

        $user_id = Yii::$app->request->post('user_id');
        $this->checkRequiredParam($user_id, 'user_id');

        $authenticatedUser_id = Yii::$app->user->id;

        if ($authenticatedUser_id != $user_id) {
            $this->sendErrorResponse(403, 'Token não corresponde ao utilizador fornecido.');
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
        $produtosCarrinho->subtotal = $quantidade * ($preco_venda + $valor_iva);
        $produtosCarrinho->produtos_id = $produtos_id;
        $produtosCarrinho->carrinhos_id = $existingCart->id;

        if ($produtosCarrinho->save()) {
            return [
                'message' => 'Linha do carrinho criada com sucesso.',
                'linhaCarrinho' => $this->mapProdutosCarrinhosResponse($produtosCarrinho)
            ];
        } else {
            $this->sendErrorResponse(500, 'Erro ao criar o produto no carrinho.', $produtosCarrinho->getErrors());
        }
        return $produtosCarrinho;
    }

    public function actionUpdateCartLine($id)
    {
        $cartline = ProdutosCarrinhos::findOne($id);
        if (!$cartline) {
            $this->sendErrorResponse(404, 'Produto no carrinho não encontrado.');
        }

        $authenticatedUser_id = Yii::$app->user->id;
        $user_id = $cartline->carrinhos->user_id;

        if ($authenticatedUser_id != $user_id) {
            $this->sendErrorResponse(403, 'Token não corresponde ao utilizador fornecido.');
        }

        $quantidade = Yii::$app->request->post('quantidade');
        $this->checkRequiredParam($quantidade, 'quantidade');
        $cartline->quantidade = $quantidade;

        $cartline->subtotal = $cartline->quantidade * ($cartline->preco_venda + $cartline->valor_iva);

        if ($cartline->save()) {
            $this->updateCartTotal($cartline->carrinhos_id);

            return [
                'message' => 'Linha do carrinho atualizada com sucesso.',
                'linhaCarrinho' => $this->mapProdutosCarrinhosResponse($cartline)
            ];
        } else {
            $this->sendErrorResponse(500, 'Erro ao atualizar o produto no carrinho.', $cartline->getErrors());
        }
    }

    public function actionDeleteCartLine($id)
    {
        $produtosCarrinho = ProdutosCarrinhos::findOne($id);
        if (!$produtosCarrinho) {
            $this->sendErrorResponse(404, 'linha no carrinho não encontrada.');
        }

        $authenticatedUser_id = Yii::$app->user->id;
        $user_id = $produtosCarrinho->carrinhos->user_id;

        if ($authenticatedUser_id != $user_id) {
            $this->sendErrorResponse(403, 'Token não corresponde ao utilizador fornecido.');
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$produtosCarrinho->delete()) {
                throw new \Exception('Erro ao eliminar a linha no carrinho.');
            }

            $this->updateCartTotal($produtosCarrinho->carrinhos_id);

            $transaction->commit();

            return [
                'message' => 'Linha do carrinho ' . $id . ' removida com sucesso.',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->sendErrorResponse(500, 'Erro ao remover o produto do carrinho.', ['error' => $e->getMessage()]);
        }
    }


    protected function updateCartTotal($id)
    {
        $cart = Carrinhos::findOne($id);
        if (!$cart) {
            $this->sendErrorResponse(404, 'Carrinho não encontrado.');
        }

        $cartLines = ProdutosCarrinhos::find()->where(['carrinhos_id' => $id])->all();
        $newTotal = 0;
        foreach ($cartLines as $line) {
            $newTotal += $line->subtotal;
        }

        $cart->valortotal = $newTotal;
        if (!$cart->save()) {
            $this->sendErrorResponse(500, 'Erro ao atualizar o total do cart.', $cart->getErrors());
        }
    }


    public function actionGetCartLinesByCartid($id)
    {
        $existingCart = Carrinhos::find()->where(['id' => $id, 'status' => "Ativo"])
            ->orderBy(['dtapedido' => SORT_DESC])->one();

        if (!$existingCart) {
            $this->sendErrorResponse(404, 'Carrinho não encontrado  com o ID ' . $id);
        }
        $produtosCarrinho = ProdutosCarrinhos::find()->where(['carrinhos_id' => $id])->all();
        if (!$produtosCarrinho && !$existingCart) {
            $this->sendErrorResponse(404, 'linhas do carrinho não encontradas para o carrinho com o ID ' . $id);
        }

        return [
            'message' => 'Linhas do carrinho encontradas associadas ao carrinho com o ID ' . $id,
            'linhaCarrinho' => $produtosCarrinho
        ];

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

    private function mapProdutosCarrinhosResponse($produtosCarrinho)
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
}
