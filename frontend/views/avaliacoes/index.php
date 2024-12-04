<?php

use common\models\Avaliacoes;
use common\models\Produtos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Avaliações';
?>
<div class="avaliacoes-index">

    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="<?= Url::to(['perfil/index']) ?>">
                <i class="fa fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
    <br>
    <h1>Produtos comprados</h1>

    <?php if (!empty($linhasFatura)): ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th colspan="2">Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Preço Total (c/ IVA)</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($linhasFatura as $linha): ?>
                <?php $produto = Produtos::findOne($linha->produtos_id); ?>
                <tr>
                    <td>
                        <?php if (!empty($produto->imagens)): ?>
                            <a href="<?= Url::to(['produtos/view', 'id' => $produto->id]) ?>">
                                <?= Html::img('@web/images/' . $produto->imagens[0]->fileName, ['class' => 'img-thumbnail', 'style' => 'max-width: 70px;']) ?>
                            </a>
                        <?php else: ?>
                            <a href="<?= Url::to(['produtos/view', 'id' => $produto->id]) ?>">
                                <?= Html::img('@web/images/default.png', ['class' => 'img-thumbnail', 'style' => 'max-width: 70px;']) ?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= Url::to(['produtos/view', 'id' => $produto->id]) ?>">
                            <?= Html::encode($produto->nome) ?>
                        </a>
                    </td>
                    <td><?= Html::encode($linha->quantidade) ?></td>
                    <td><?= number_format($linha->produtos->preco, 2, ',', '.') ?> €</td>
                    <td><?= number_format($linha->subtotal, 2, ',', '.') ?> €</td>
                    <td>
                        <?= Html::a('Avaliar', ['avaliacoes/create', 'linhaFaturaId' => $linha->id], [
                            'class' => 'btn btn-success',
                        ]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="text-center mt-5">
            <h3 class="mt-3 text-muted">Ainda não fez compras!</h3>
            <p class="text-muted">Explore os nossos produtos e faça a sua primeira compra.</p>
            <?= Html::a('Explorar Produtos', ['produtos/index'], ['class' => 'btn btn-success']) ?>
        </div>
    <?php endif; ?>
</div>
