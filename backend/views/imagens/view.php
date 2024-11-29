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
        <?= Html::a('Update', ['update', 'id' => $model->id, 'produto_id' => $model->produto_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'produto_id' => $model->produto_id], [
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
            'fileName:text:Ficheiro',
            'produto.nome:text:Produto',
        ],
    ]) ?>

    <?= Html::img('@web/images/' . $model->fileName, ['alt' => 'Imagens', 'style' => 'max-width:300px;']); ?>
</div>
