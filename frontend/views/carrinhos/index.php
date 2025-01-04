<?php

use common\models\Carrinhos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\CarrinhosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Carrinhos';
?>
<div class="carrinhos-index">

<!--    --><?php //= GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'id',
//            'dtapedido',
//            'metodo_envio',
//            'status',
//            'valortotal',
//            //'user_id',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, Carrinhos $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
//        ],
//    ]); ?>
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-12 table-responsive">
                    <?php foreach ($dataProvider->getModels() as $carrinho): ?>

                    <table class="table">
                        <thead>
                        <?php if (empty($carrinho->produtosCarrinhos)) { ?>
                            <h2>Carrinho Vazio</h2>
                        <?php } ?>
                        <tr>
                            <th scope="col">Produto</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Preço c/IVA</th>
                            <th scope="col">IVA</th>
                            <th scope="col">Quantidade</th>
                            <th scope="col">Sub-Total</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <?php
                            $iva = 0;
                            foreach ($carrinho->produtosCarrinhos as $produtoCarrinho):
                            $iva += $produtoCarrinho->valor_iva * $produtoCarrinho->quantidade;
                            if (!empty($produtoCarrinho->produtos->imagens)) : ?>
                            <div class="d-flex align-items-center">
                                <td> <?= Html::img(
                                        Url::to('@web/images/' . $produtoCarrinho->produtos->imagens[0]->fileName),
                                        ['class' => 'img-fluid me-5'] + ['width' => '100px']
                                    ) ?></td>
                                <?php else : ?>
                                    <td><?= Html::img(
                                            Url::to('@web/images/default.png'),
                                            ['class' => 'img-fluid me-5'] + ['width' => '100px']
                                        ) ?></td>
                                <?php endif; ?>
                            </div>

                            <td><?= Html::encode($produtoCarrinho->produtos->nome) ?></td>
                            <td><?= Html::encode($produtoCarrinho->produtos->descricao) ?></td>
                            <td><?= Html::encode(number_format($produtoCarrinho->produtos->preco, 2, ',', '.'). '€' )?></td>
                            <td><?= Html::encode($produtoCarrinho->produtos->ivas->percentagem) . '%' ?></td>
                            <td>
                                <div class="input-group quantity mt-0" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-minus rounded-circle bg-light">
                                            <?= Html::a('<span class="fas fa-minus"></span>', ['carrinhos/diminuir', 'id' => $produtoCarrinho->id]); ?>
                                        </button>
                                    </div>
                                    <?= Html::input('text', 'quantidade', $produtoCarrinho->quantidade, ['class' => 'form-control form-control-sm text-center border-0']) ?>
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-plus rounded-circle bg-light">
                                            <?= Html::a('<span class="fas fa-plus"></span>', ['carrinhos/aumentar', 'id' => $produtoCarrinho->id]); ?>
                                        </button>
                                    </div>
                                </div>
                            </td>


                            <td><?= Html::encode(number_format($produtoCarrinho->subtotal, 2, ',', '.')) . ' €' ?></td>
                            <td><?= Html::a('<span class="fas fa-trash"></span>', ['produtos-carrinhos/delete', 'id' => $produtoCarrinho->id], ['data' => ['confirm' => 'Tem a certeza que pretende remover este produto do carrinho?', 'method' => 'post',]]); ?></td>
                            <!-- Add more cells for other columns -->
                        </tr>
                        <?php endforeach; ?>
                        <?php endforeach; ?>


                        </tbody>
                    </table>
                    <br>
                    <br>
                    <div class="row g-4 justify-content-end">
                        <div class="col-8"></div>
                        <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                            <div class="bg-light rounded">
                                <div class="p-4">
                                    <h1 class="display-6 mb-4">Carrinho</h1>
                                    <div class="d-flex justify-content-between mb-4">
                                        <h5 class="mb-0 me-4">Subtotal:</h5>
                                        <p class="mb-0"><?= Html::encode(number_format($carrinho->valortotal - $iva, 2, ',', '.')) . '€' ?></p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0 me-4">IVA</h5>
                                        <div class="">
                                            <p class="mb-0"><?= Html::encode(number_format($iva, 2, ',', '.')) . '€' ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                                    <h5 class="mb-0 ps-4 me-4">Total</h5>
                                    <p class="mb-0 pe-4"><?= Html::encode(number_format($carrinho->valortotal, 2, ',', '.')) . '€' ?></p>
                                </div>
                                <?php if (empty($carrinho->produtosCarrinhos)) { ?>
                                    <button class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4 "
                                            type="button">
                                        <?= Html::a('Voltar para a loja', ['produtos/index'], ['class' => 'text-decoration-none']) ?>
                                    </button>
                                <?php } else { ?>
                                    <button class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4 "
                                            type="button">
                                        <?= Html::a(' Finalizar compra', ['carrinhos/checkout', 'id' => $carrinho->id], ['class' => 'text-decoration-none']) ?>
                                    </button>

                                    <button class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4 "
                                            type="button">
                                        <?= Html::a(' Continuar a comprar', ['produtos/index'], ['class' => 'text-decoration-none']) ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
