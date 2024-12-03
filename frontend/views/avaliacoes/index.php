<?php

use common\models\Avaliacoes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Avaliações';
?>
<div class="avaliacoes-index">

        <h1>Produtos comprados</h1>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th colspan="2">Produto</th>
                <th>Quantidade</th>
                <th>Preço Total</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($linhasFatura as $linha): ?>
                <tr>
                    <td>
                        <?php if (!empty($linha->produtos->imagens)): ?>
                            <?= Html::img('@web/images/' . $linha->produtos->imagens[0]->fileName, ['class' => 'img-thumbnail', 'style' => 'max-width: 70px;']) ?>
                        <?php else: ?>
                            <?= Html::img('@web/images/default.png', ['class' => 'img-thumbnail', 'style' => 'max-width: 70;']) ?>
                        <?php endif; ?>
                    </td>
                    <td><?= Html::encode($linha->produtos->nome) ?></td>
                    <td><?= Html::encode($linha->quantidade) ?></td>
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
</div>
