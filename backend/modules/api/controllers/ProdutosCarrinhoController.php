<?php

namespace backend\modules\api\controllers;

use common\models\Carrinhos;
use common\models\Produtos;
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

    public function actionCreateCartLine()
    {
        $products_id = (int)Yii::$app->request->post('produtos_id');
        $this->checkRequiredParam($products_id, 'produtos_id');

        $user_id = Yii::$app->user->id;

        $existingCart = Carrinhos::find()->where(['user_id' => $user_id, 'status' => "Ativo"])
            ->orderBy(['dtapedido' => SORT_DESC])->one();

        if (!$existingCart) {
            $this->sendErrorResponse(404, 'Carrinho não encontrado para o utilizador com o ID ' . $user_id);
        }
        $product = Produtos::findOne($products_id);

        $existingCartLines = ProdutosCarrinhos::find()->where(['carrinhos_id' => $existingCart->id, 'produtos_id' => $products_id])->one();
        if ($existingCartLines) {
            $existingCartLines->quantidade = (intval($existingCartLines->quantidade) + 1) . '';
            $existingCartLines->valor_iva = $product->preco * ($product->ivas->percentagem / 100);
            $existingCartLines->subtotal = $existingCartLines->quantidade * $product->preco;
            $existingCartLines->save();
            $this->updateCartTotal($existingCart->id);
            return $this->mapProdutosCarrinhosResponse($existingCartLines, 'Quantidade da linha do carrinho atualizada com sucesso.');
        } else {
            $cartLines = new ProdutosCarrinhos();
            $cartLines->quantidade = "1";
            $cartLines->preco_venda = $product->preco;
            $cartLines->valor_iva = $product->preco * ($product->ivas->percentagem / 100);
            $cartLines->subtotal = $cartLines->quantidade * $product->preco;
            $cartLines->produtos_id = $products_id;
            $cartLines->carrinhos_id = $existingCart->id;

            if ($cartLines->save()) {
                $this->updateCartTotal($existingCart->id);
                $this->mapProdutosCarrinhosResponse($cartLines, 'Linha do carrinho criada com sucesso.');
            } else {
                $this->sendErrorResponse(500, 'Erro ao criar o produto no carrinho.', $cartLines->getErrors());
            }
            return $this->mapProdutosCarrinhosResponse($cartLines, 'Linha do carrinho criada com sucesso.');
        }

    }

    public function actionGetCartLinesByUserid($id)
    {
        $authenticatedUserId = Yii::$app->user->id;

        $existingCart = Carrinhos::find()->where(['user_id' => $id, 'status' => "Ativo"])
            ->orderBy(['dtapedido' => SORT_DESC])->one();


        if (!$existingCart) {
            $this->sendErrorResponse(404, 'Carrinho não encontrado  com o ID ' . $id);
        }

        if ($existingCart->user_id != $authenticatedUserId) {
            $this->sendErrorResponse(403, 'Token não corresponde ao utilizador fornecido.');
        }

        $produtosCarrinho = ProdutosCarrinhos::find()->where(['carrinhos_id' => $existingCart->id])->all();
        if (!$produtosCarrinho && !$existingCart) {
            $this->sendErrorResponse(404, 'linhas do carrinho não encontradas para o carrinho com o ID ' . $id);
        }

        return $produtosCarrinho;


    }

    public function actionIncreaseQuantityCartline($id)
    {
        $user_id = Yii::$app->user->id;

        $existingCart = Carrinhos::find()->where(['user_id' => $user_id, 'status' => "Ativo"])
            ->orderBy(['dtapedido' => SORT_DESC])->one();

        if (!$existingCart) {
            $this->sendErrorResponse(404, 'Carrinho "Ativo" não encontrado associado ao utilizador:' . $user_id);
        }
        $existingCartLine =
            ProdutosCarrinhos::find()->where(['id' => $id, 'carrinhos_id' => $existingCart->id])->one();

        $product = Produtos::findOne($existingCartLine->produtos_id);
        if (!$existingCartLine) {
            $this->sendErrorResponse(404, 'Linha do Carrinho não encontrada');
        }

        $existingCartLine->quantidade = (intval($existingCartLine->quantidade) + 1) . '';
        $existingCartLine->subtotal = $existingCartLine->quantidade * $product->preco;
        $existingCartLine->valor_iva = $product->preco * ($product->ivas->percentagem / 100);

        if ($existingCartLine->save()) {
            $this->updateCartTotal($existingCart->id);
            return [
                'message' => 'Quantidade da Linha do Carrinho atualizada com sucesso',
                'linhaCarrinho' => $existingCartLine,
            ];
        } else {
            $this->sendErrorResponse(500, "Erro ao Atualizar a Quantidade da Linha do Carrinho " . $id);
            $existingCartLine->getErrors();
        }
        return $existingCartLine;
    }

    public function actionDecreaseQuantityCartline($id)
    {
        $user_id = Yii::$app->user->id;

        $existingCart = Carrinhos::find()->where(['user_id' => $user_id, 'status' => "Ativo"])
            ->orderBy(['dtapedido' => SORT_DESC])->one();

        if (!$existingCart) {

            $this->sendErrorResponse(404, 'Carrinho "Ativo" não encontrado associado ao utilizador:' . $user_id);
        }
        $existingCartLine =
            ProdutosCarrinhos::find()->where(['id' => $id, 'carrinhos_id' => $existingCart->id])->one();

        $product = Produtos::findOne($existingCartLine->produtos_id);
        if (!$existingCartLine) {
            $this->sendErrorResponse(404, 'Linha do Carrinho não encontrada');
        }

        if ($existingCartLine->quantidade == 1) {
            $this->sendErrorResponse(400, 'Quantidade mínima atingida', ['quantidade' => $existingCartLine->quantidade]);
        }
        $existingCartLine->quantidade = (intval($existingCartLine->quantidade) - 1) . '';
        $existingCartLine->subtotal = $existingCartLine->quantidade * $product->preco;
        $existingCartLine->valor_iva = $product->preco * ($product->ivas->percentagem / 100);

        if ($existingCartLine->save()) {
            $this->updateCartTotal($existingCart->id);
            return [
                'message' => 'Quantidade da Linha do Carrinho atualizada com sucesso',
                'linhaCarrinho' => $existingCartLine,
            ];
        } else {
            $this->sendErrorResponse(500, "Erro ao Atualizar a Quantidade da Linha do Carrinho " . $id);
            $existingCartLine->getErrors();
        }
        return $existingCartLine;
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

    protected function mapProdutosCarrinhosResponse($cartLine, $message = null)
    {
        $response = [
            'message' => $message,
            'id' => $cartLine->id,
            'quantidade' => $cartLine->quantidade,
            'preco_venda' => $cartLine->preco_venda,
            'valor_iva' => $cartLine->valor_iva,
            'subtotal' => $cartLine->subtotal,
            'produtos_id' => $cartLine->produtos_id,
            'carrinhos_id' => $cartLine->carrinhos_id,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return $response;
    }
}
