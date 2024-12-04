<?php

use common\models\Produtos;
use common\models\CategoriasProdutos;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\data\Pagination;

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
            <h1 class="h2 pb-4">Categorias:</h1>
            <ul class="list-unstyled">
                <li class="pb-3">
                    <?php
                    $isActive = Yii::$app->request->get('categorias_id') === null ? 'text-primary font-weight-bold' : '';
                    ?>
                    <a class="d-flex justify-content-between text-decoration-none <?= $isActive ?>"
                       href="<?= Url::to(['/produtos/index']) ?>">
                        (<?= Produtos::find()->count() ?>) Mostrar Todos
                    </a>
                </li>
                <?php foreach ($categorias as $categoria): ?>
                    <?php
                    $isActive = (Yii::$app->request->get('categorias_id') == $categoria->id) ? 'text-primary font-weight-bold' : '';
                    ?>
                    <li class="pb-3">
                        <a class="d-flex justify-content-between text-decoration-none <?= $isActive ?>"
                           href="<?= Url::to(['produtos/index', 'categorias_id' => $categoria->id]) ?>">
                            (<?= $categoria->getProdutos()->count() ?>) <?= Html::encode($categoria->nome) ?>
                        </a>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>

        <!-- Produtos -->
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-6 pb-4">
                    <div class="d-flex">
                        <?php $form = ActiveForm::begin(['action' => ['produtos/index'], 'method' => 'get']); ?>
                        <div class="input-group mb-3">
                            <?= Html::input('text', 'search', $search, ['class' => 'form-control', 'placeholder' => 'Pesquisar produtos...']) ?>
                            <?= Html::submitButton(
                                '<i class="fa fa-fw fa-search text-light mr-3"></i>',
                                ['class' => 'btn btn-success']
                            ) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ($dataProvider->getModels() as $produto): ?>
                    <div class="col-md-4">
                        <div class="card mb-4 product-wap rounded-0">
                            <div class="card rounded-0">
                                <img class="card-img rounded-0 img-fluid"
                                     src="<?= Url::to('@web/images/' . (!empty($produto->imagens) && isset($produto->imagens[0]->fileName)
                                             ? $produto->imagens[0]->fileName
                                             : 'default.png')) ?>"
                                     alt="<?= Html::encode($produto->nome) ?>">
                                <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                    <ul class="list-unstyled">
                                        <li><a class="btn btn-success text-white mt-2"
                                               href="<?= Url::to(['produtos/view', 'id' => $produto->id]) ?>"><i
                                                        class="far fa-eye"></i></a></li>
                                        <li><a class="btn btn-success text-white mt-2"
                                               href="<?= Url::to(['produtos-carrinhos/create', 'produtos_id' => $produto->id]) ?>"><i
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


            <!-- Paginação -->
            <div class="row">
                <?php
                $pagination = $dataProvider->pagination;

                // Verifica se há mais de uma página antes de renderizar a paginação
                if ($pagination->pageCount > 1) {
                    echo '<ul class="pagination pagination-lg justify-content-end">';
                    for ($page = 0; $page < $pagination->pageCount; $page++) {
                        $activeClass = $pagination->page == $page ? 'active' : '';
                        echo '<li class="page-item ' . $activeClass . '">';
                        echo Html::a($page + 1, $pagination->createUrl($page), ['class' => 'page-link']);
                        echo '</li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- End Content -->
