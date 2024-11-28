<?php

use common\models\Produtos;
use common\models\CategoriasProdutos;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var common\models\ProdutosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Produtos';
?>
<!-- Start Content -->
<div class="container py-5">
    <h1 class="h1 mb-4">Produtos</h1>
    <div class="row">
        <!-- Sidebar: Categorias -->
        <div class="col-lg-3">
            <br>
            <br>
            <h1 class="h2 pb-4">Categorias:</h1>
            <ul class="list-unstyled templatemo-accordion">
                <?php
                $categorias = CategoriasProdutos::find()->all();
                foreach ($categorias as $categoria): ?>
                    <li class="pb-3">
                        <a class="d-flex justify-content-between h3 text-decoration-none"
                           href="<?= Url::to(['produtos/index', 'categoria_id' => $categoria->id]) ?>">
                            <?= Html::encode($categoria->nome) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Ordem dos produtos -->
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-9">
                </div>
                <div class="col-md-3 pb-4">
                    <div class="d-flex">
                        <select class="form-control">
                            <option>Recentes</option>
                            <option>A a Z</option>
                            <option>Preço Ascendente</option>
                            <option>Preço Descendente</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Produtos -->
            <div class="col-lg-9">
                <?php
                // Configura o ActiveDataProvider com paginação
                $query = Produtos::find();
                if (isset($_GET['categoria_id'])) {
                    $query->where(['categorias_id' => $_GET['categoria_id']]);
                }

                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pageSize' => 3, // Artigos por página   mudar para 12
                    ],
                ]);

                // Obtenha os produtos para a página atual
                $produtos = $dataProvider->getModels();
                ?>

                <div class="row">
                    <?php foreach ($produtos as $produto): ?>
                        <div class="col-md-4">
                            <div class="card mb-4 product-wap rounded-0">
                                <div class="card rounded-0">
                                    <img class="card-img rounded-0 img-fluid"
                                         src="<?= Url::to('@web/images/' .
                                             (!empty($produto->imagens) && isset($produto->imagens[0]->fileName)
                                                 ? $produto->imagens[0]->fileName
                                                 : 'default.png')) ?>"
                                         alt="<?= Html::encode($produto->nome) ?>">
                                    <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                        <ul class="list-unstyled">
                                            <li><a class="btn btn-success text-white mt-2"
                                                   href="<?= Url::to(['produtos/view', 'id' => $produto->id]) ?>"><i
                                                            class="far fa-eye"></i></a></li>
                                            <li><a class="btn btn-success text-white mt-2"
                                                   href="<?= Url::to(['carrinhos/add', 'id' => $produto->id]) ?>"><i
                                                            class="fas fa-cart-plus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="<?= Url::to(['produtos/view', 'id' => $produto->id]) ?>"
                                       class="h3 text-decoration-none"><?= Html::encode($produto->nome) ?></a>
                                    <p class="text-center mb-0"><?= number_format($produto->preco, 2, ',', '.') ?> €</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <br>
            <br>
            <!-- Paginação -->
<!--            mudar para 12-->
            <?php if ($dataProvider->pagination->totalCount > 3): ?>
                <div class="row">
                    <?= LinkPager::widget([
                        'pagination' => $dataProvider->pagination,
                        'options' => ['class' => 'pagination pagination-lg justify-content-end'],
                        'linkOptions' => ['class' => 'page-link rounded-0 shadow-sm border-top-0 border-left-0 text-dark'],
                        'activePageCssClass' => 'active rounded-0 mr-3 shadow-sm border-top-0 border-left-0',
                        'disabledPageCssClass' => 'disabled',
                    ]); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- End Content -->

    </div>
</div>
<!-- End Content -->
