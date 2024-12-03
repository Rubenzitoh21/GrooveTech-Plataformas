<?php

use common\models\Produtos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ProdutosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Produtos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produtos-index">


    <p>
        <?= Html::a('Criar Produto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'nome',
            'descricao',
            [
                'attribute' => 'preco',
                'value' => function ($model) {
                    return number_format($model->preco, 2, ',', '.') . ' €';
                },
            ],
            'obs',
            //'categorias_produtos_id',
            //'ivas_id',
            [
                'attribute' => 'categorias_produtos_id',
                'value' => function ($model) {
                    return $model->categoriasProdutos->nome;
                },
            ],
            [
                'attribute' => 'ivas_id',
                'value' => function ($model) {
                    return $model->ivas->percentagem;
                },
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Produtos $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
