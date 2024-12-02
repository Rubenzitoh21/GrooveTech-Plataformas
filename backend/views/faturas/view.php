<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Faturas $model */

$this->title = $model->id;
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
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'data',
            'valortotal',
            'status',
            'user_id',
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
    <?= GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getLinhasFaturas(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'produtos.nome',
                'label' => 'Produto',
            ],
            [
                'attribute' => 'quantidade',
                'label' => 'Quantidade',
            ],
            [
                'attribute' => 'preco_venda',
                'label' => 'Preço Unitário',
                'value' => function ($model) {
                    return number_format($model->preco_venda, 2, ',', '.') . ' €';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'valor_iva',
                'label' => 'IVA',
                'value' => function ($model) {
                    return number_format($model->valor_iva, 2, ',', '.') . ' €';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'subtotal',
                'label' => 'Sub-Total',
                'value' => function ($model) {
                    return number_format($model->subtotal, 2, ',', '.') . ' €';
                },
                'format' => 'raw',
            ],
            [
                'label' => 'Total',
                'value' => function ($model) {
                    $total = $model->subtotal * $model->quantidade;
                    return number_format($total, 2, ',', '.') . ' €';
                },
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>
