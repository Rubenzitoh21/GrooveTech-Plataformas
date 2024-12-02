<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Produtos $model */

$this->title = $model->nome;
\yii\web\YiiAsset::register($this);
?>
<div class="produtos-view">

    <section class="bg-light">
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-5 mt-5">
                    <div class="card mb-3">
                        <img class="card-img img-fluid" src="<?= Url::to('@web/images/' .
                            (!empty($model->imagens) && isset($model->imagens[0]->fileName)
                                ? $model->imagens[0]->fileName
                                : 'default.png')) ?>"
                             alt="<?= Html::encode($model->nome) ?>">
                    </div>

                </div>
                <!-- col end -->
                <div class="col-lg-7 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="h2"><?= Html::encode($model->nome) ?></h1>
                            <p class="h3 py-2"><?= number_format($model->preco, 2, ',', '.') ?> €</p>
                            <p class="py-2">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-secondary"></i>
                                <span class="list-inline-item text-dark">Rating 4.8 | 36 Comments</span>
                            </p>

                            <h6>Categoria:</h6>
                            <h4><?= Html::encode($model->categoriasProdutos->nome) ?></h4>

                            <h6>Descrição:</h6>
                            <h4><?= Html::encode($model->descricao) ?></h4>

                            <h6>Observações:</h6>
                            <h4><?= Html::encode($model->obs) ?></h4>
                            <br>
                            <br>
                            <div class="row pb-3">
                                <div class="col d-grid">
                                    <a class="btn btn-success btn-lg"
                                       href="<?= Url::to(['produtos-carrinhos/create', 'produtos_id' => $model->id]) ?>">Adicionar ao Carrinho</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Close Content -->

</div>
