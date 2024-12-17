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
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

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
                'format' => 'raw', // Permite usar HTML
                'value' => function ($model) {
                    if ($model->categoriasProdutos) {
                        return Html::a(
                            Html::encode($model->categoriasProdutos->nome),
                            ['categorias-produtos/view', 'id' => $model->categoriasProdutos->id],
                        );
                    }
                    return 'Categoria não disponível';
                },
            ],
            [
                'attribute' => 'ivas_id',
                'format' => 'raw', // Permite usar HTML
                'value' => function ($model) {
                    if ($model->ivas) {
                        return Html::a(
                            Html::encode($model->ivas->percentagem . '%'),
                            ['ivas/view', 'id' => $model->ivas->id],
                        );
                    }
                    return 'IVA não disponível';
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
