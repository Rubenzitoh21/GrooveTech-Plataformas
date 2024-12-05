<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Produtos $model */
/** @var common\models\Avaliacoes[] $avaliacoes */


$linhaFaturaId = Yii::$app->controller->getUltimaLinhaFatura($model->id);
$this->title = $model->nome;
\yii\web\YiiAsset::register($this);
?>
<div class="produtos-view">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="<?= Url::to(['produtos/index']) ?>">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
        <br>
    <section class="bg-light">
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-5 mt-5">
                    <img class="card-img img-fluid" src="<?= Url::to('@web/images/' .
                        (!empty($model->imagens) && isset($model->imagens[0]->fileName)
                            ? $model->imagens[0]->fileName
                            : 'default.png')) ?>"
                         alt="<?= Html::encode($model->nome) ?>">
                </div>
                <div class="col-lg-7 mt-5">
                    <div class="card h-100 d-flex flex-column">
                        <div class="card-body flex-grow-1">
                            <h1 class="h2"><?= Html::encode($model->nome) ?></h1>
                            <p class="h3 py-2"><?= number_format($model->preco, 2, ',', '.') ?> €</p>

                            <?php
                            $rating = $model->getAvaliacoes()->average('rating') ?: 0;
                            $totalComentarios = $model->getAvaliacoes()->count();
                            ?>
                            <p class="py-2">
                                <?php if ($totalComentarios > 0): ?>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa fa-star <?= $i <= round($rating) ? 'text-warning' : 'text-secondary' ?>"></i>
                                    <?php endfor; ?>
                                    <span class="list-inline-item text-dark">
                                        Avaliação <?= number_format($rating, 1) ?> | <?= $totalComentarios ?> Comentários
                                    </span>
                                <?php else: ?>
                                    <span class="list-inline-item text-muted">
                                        Este produto ainda não possui avaliações.
                                    </span>
                                <?php endif; ?>
                                <?php if ($foiComprado): ?>
                                    <a class="btn btn-success"
                                       href="<?= Url::to(['avaliacoes/create', 'linhaFaturaId' => $ultimaLinhaFaturaId]) ?>">
                                        Avaliar Produto
                                    </a>
                                <?php endif; ?>
                            </p>

                            <h6>Categoria:</h6>
                            <h4><?= Html::encode($model->categoriasProdutos->nome) ?></h4>
                            <br>
                            <h6>Descrição:</h6>
                            <h4><?= Html::encode($model->descricao) ?></h4>
                            <br>
                            <h6>Observações:</h6>
                            <h4><?= Html::encode($model->obs) ?></h4>
                        </div>

                        <!-- Verifica se o produto foi comprado -->
                        <div class="card-footer mt-auto bg-white border-0">
                            <a class="btn btn-success btn-lg w-100"
                               href="<?= Url::to(['produtos-carrinhos/create', 'produtos_id' => $model->id]) ?>">
                                Adicionar ao Carrinho
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Comentários -->
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card review-card">
                        <div class="card-body">
                            <h3 class="h3">Comentários</h3>
                            <?php if ($totalComentarios > 0): ?>
                                <ul class="list-group">
                                    <?php foreach ($model->getAvaliacoes()->all() as $avaliacao): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5><?= Html::encode($avaliacao->user->userProfile->primeironome . ' ' . $avaliacao->user->userProfile->apelido) ?>
                                                    <small class="text-muted">
                                                        (<?= Yii::$app->formatter->asDate($avaliacao->dtarating, 'php:d/m/Y') ?>)
                                                    </small>
                                                </h5>
                                                <p><?= Html::encode($avaliacao->comentario) ?></p>
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fa fa-star <?= $i <= $avaliacao->rating ? 'text-warning' : 'text-secondary' ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <?php if ($avaliacao->user_id == Yii::$app->user->id): ?>
                                                <div>
                                                    <?= Html::a(
                                                        '<i class="fa fa-trash text-danger"></i>',
                                                        ['avaliacoes/delete', 'id' => $avaliacao->id],
                                                        [
                                                            'data' => [
                                                                'confirm' => 'Tem certeza de que deseja excluir a sua avaliação?',
                                                                'method' => 'post',
                                                            ],
                                                            'class' => 'text-decoration-none',
                                                        ]
                                                    ) ?>
                                                </div>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted">Este produto ainda não possui comentários.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>