<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Faturas $model */

$this->title = "Fatura #".$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Faturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="faturas-view">


    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Anular', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem a certeza que pretende anular esta fatura?',
                'method' => 'post',
            ],

        ])?>
        <?= Html::a('<i class="fa fa-print"></i> Imprimir Fatura', ['faturas/print', 'id' => $model->id], [
            'class' => 'btn btn-secondary',
            'target' => '_blank',
            'rel' => 'noopener noreferrer',
        ]) ?>

        <?php //echo Html::a('Imprimir', ['imprimir'], ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'data',
            [
                'attribute' => 'valortotal',
                'label' => 'Valor Total',
                'value' => function ($model) {
                    return number_format($model->valortotal, 2, ',', '.') . ' €';
                },
            ],
            'status',
            [
                'attribute' => 'user_id',
                'label' => 'Utilizador',
                'format' => 'raw',
                'value' => function ($model) {
                    $user = $model->user;
                    if ($user) {
                        return Html::a(
                            Html::encode($user->userProfile->primeironome . ' ' . $user->userProfile->apelido),
                            ['user-profile/view', 'id' => $user->userProfile->id, 'user_id' => $user->id],
                        );
                    }
                    return 'Utilizador não disponível';
                },
            ],
            [
                'attribute' => 'pagamentos_id',
                'label' => 'Método de Pagamento',
                'value' => function ($model) {
                    return $model->pagamentos->metodopag;
                },
            ],
            [
                'attribute' => 'expedicoes_id',
                'label' => 'Método de Expedição',
                'value' => function ($model) {
                    return $model->expedicoes->metodoexp;
                },
            ],
        ],
    ]) ?>
    <br>
    <h3>Linhas da Fatura</h3>
    <table class="table table-bordered table-striped">
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço Unitáraio</th>
            <th>Iva</th>
            <th>Sub-Total</th>
            <th>Total</th>
        </tr>
        <?php foreach ($model->linhasFaturas as $linhaFatura): ?>
            <tr>
                <td><a href="<?= Url::to(['/produtos/view', 'id' => $linhaFatura->produtos->id]); ?>"><?= $linhaFatura->produtos->nome ?></a></td>
                <td><?= $linhaFatura->quantidade ?></td>
                <td><?= number_format($linhaFatura->preco_venda, 2, ',', '.') ?> €</td>
                <td><?= number_format($linhaFatura->valor_iva * $linhaFatura->quantidade, 2, ',', '.') ?> €</td>
                <td><?= number_format($linhaFatura->subtotal - ($linhaFatura->valor_iva * $linhaFatura->quantidade), 2, ',', '.') ?> €</td>
                <td><?= number_format($linhaFatura->subtotal, 2, ',', '.') ?> €</td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
