<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Produtos $model */

$this->title = $model->nome;
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
        <?= Html::a('Adicionar Imagem', ['imagens/create', 'produto_id' => $model->id, 'urlCallback' => 'produto'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'nome',
            'descricao',
            [
                'attribute' => 'preco',
                'value' => function ($model) {
                    return number_format($model->preco, 2, ',', '.') . ' €';
                },
            ],
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
            [
                'attribute' => 'image',
                'label' => 'Imagem',
                'format' => 'html',
                'value' => function ($model) {
                    if (!empty($model->imagens)) {
                        $images = '';
                        foreach ($model->imagens as $imagem) {
                            $images .= Html::img('@web/images/' . $imagem->fileName, ['class' => 'img-thumbnail', 'style' => 'max-width: 150px; margin-right: 5px;']);
                        }
                        return $images;
                    }
                    return 'Sem imagem disponível';
                },
            ],
        ],
    ]) ?>
</div>
