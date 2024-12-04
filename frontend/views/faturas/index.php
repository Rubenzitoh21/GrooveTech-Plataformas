<?php

use common\models\Faturas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Faturas';
?>
<div class="faturas-index">
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="<?= Url::to(['perfil/index']) ?>">
                <i class="fa fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
    <br>
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!empty($dataProvider->getModels())): ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Data</th>
                <th>Valor Total</th>
                <th>Estado</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dataProvider->getModels() as $fatura): ?>
                <tr>
                    <td><?= Yii::$app->formatter->asDate($fatura->data, 'php:d/m/Y') ?></td>
                    <td><?= number_format($fatura->valortotal, 2, ',', '.') ?> €</td>
                    <td><?= Html::encode($fatura->status) ?></td>
                    <td>
                        <?= Html::a('Ver Fatura', ['faturas/view', 'id' => $fatura->id], ['class' => 'btn btn-success']) ?>
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

