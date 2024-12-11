<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Imagens $model */

$this->title = $model->produto->nome;
$this->params['breadcrumbs'][] = ['label' => 'Imagens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="imagens-view">

    <p>
        <?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem a certeza que quer eliminar esta imagem?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fileName',
            'produto.nome',
        ],
    ]) ?>

    <?= Html::img('@web/images/' . $model->fileName, ['alt' => 'Imagens', 'style' => 'max-width:300px;']); ?>
</div>
