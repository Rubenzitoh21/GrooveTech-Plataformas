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
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <p>
        <?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem a certeza que pretende eliminar este Produto?',
                'method' => 'post',
            ],
        ]) ?>
        <?php if (empty($model->imagens)): // Verifica se não há imagens ?>
            <?= Html::a('Adicionar Imagem', ['imagens/create', 'produto_id' => $model->id, 'urlCallback' => 'produto'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
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
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->categoriasProdutos) {
                        return Html::a(
                            Html::encode($model->categoriasProdutos->nome),
                            ['categorias-produtos/view', 'id' => $model->categoriasProdutos->id],
                        );
                    }
                    return 'Categoria não disponível';
                },
            ],
            [
                'attribute' => 'ivas_id',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->ivas) {
                        return Html::a(
                            Html::encode($model->ivas->percentagem . '%'),
                            ['ivas/view', 'id' => $model->ivas->id],
                        );
                    }
                    return 'IVA não disponível';
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
                            $images .= Html::a(
                                Html::img('@web/images/' . $imagem->fileName, ['class' => 'img-thumbnail', 'style' => 'max-width: 150px; margin-right: 5px;']),
                                ['imagens/view', 'id' => $imagem->id]
                            );
                        }
                        return $images;
                    }
                    return 'Sem imagem disponível';
                },
            ],

        ],
    ]) ?>
</div>
