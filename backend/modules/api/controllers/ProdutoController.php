<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class ProdutoController extends ActiveController
{
    public $modelClass = 'common\models\Produtos';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAllproducts()
    {
        // Fetch all products with associated category, iva, and image in a single query
        $produtos = $this->modelClass::find()
            ->joinWith(['categoriasProdutos', 'ivas', 'imagens']) // Assuming these relations are defined in the model
            ->all();

        if (empty($produtos)) {
            throw new NotFoundHttpException("Não existem produtos");
        }

        // Use ActiveRecord's toArray method to convert the result to an array
        $produtosArray = array_map(function ($produto) {
            // You can customize how related data is fetched by accessing the relationships
            return [
                'id' => $produto->id,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'descricao' => $produto->descricao,
                'obs' => $produto->obs,
                'iva' => $produto->ivas ? $produto->ivas->percentagem : 0,  // Default to 0 if iva is not found
                'categoria' => $produto->categoriasProdutos ? $produto->categoriasProdutos->nome : 'Desconhecida',
                // Default to 'Desconhecida' if category is not found
                'imagem' => $produto->imagens ? $produto->imagens[0]->fileName : 'sem imagem',  // Default to 'sem imagem' if no image
            ];
        }, $produtos);

        return $produtosArray;
    }

    public function actionSearch($query)
    {
        $produtos = $this->modelClass::find()
            ->joinWith(['categoriasProdutos', 'ivas', 'imagens'])
            ->where(['or',
                ['like', 'produtos.nome', $query],
                ['like', 'categorias_produtos.nome', $query] // Adicionando a pesquisa por categoria
            ])
            ->all();

        if (empty($produtos)) {
            throw new NotFoundHttpException("Não foram encontrados produtos para o termo '$query'");
        }

        return array_map(function ($produto) {
            return [
                'id' => $produto->id,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'descricao' => $produto->descricao,
                'obs' => $produto->obs,
                'iva' => $produto->ivas ? $produto->ivas->percentagem : 0,
                'categoria' => $produto->categoriasProdutos ? $produto->categoriasProdutos->nome : 'Desconhecida',
                'imagem' => $produto->imagens ? $produto->imagens[0]->fileName : 'sem imagem',
            ];
        }, $produtos);
    }


}
