<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Produtos $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="produtos-view">


    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem a certeza que pretende eliminar este Produto?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            'Descrição' => 'descricao',
            'Preço (€)' => 'preco',
            'obs',
            //'categorias_produtos_id',
            //'ivas_id',
            [
                'attribute' => 'categorias_produtos_id',
                'label' => 'Categoria',
                'value' => function ($model) {
                    return $model->categoriasProdutos->nome;
                },
            ],
            [
                'attribute' => 'ivas_id',
                'label' => 'IVA (%)',
                'value' => function ($model) {
                    return $model->ivas->percentagem;
                },
            ],
        ],
    ]) ?>

</div>
