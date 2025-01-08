<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\CategoriasProdutos $model */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Categorias Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="categorias-produtos-view">
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
                'confirm' => 'Tem a certeza que pretende eliminar esta Categoria?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'nome',
            'obs',
        ],
    ]) ?>

    <?php if (!empty($model->produtos)): ?>
    <br>
    <h3>Produtos</h3>
    <table class="table table-bordered table-striped">
        <tr>
            <th>Nome</th>
            <th>Descição</th>
            <th>Observações</th>
            <th>Preço</th>
        </tr>
            <?php foreach ($model->produtos as $produto): ?>
        <tr>
            <td><a href="<?= Url::to(['/produtos/view', 'id' => $produto->id]); ?>"><?= $produto->nome ?></a></td>
            <td><?= $produto->descricao ?></td>
            <td><?= $produto->obs ?></td>
            <td><?= number_format($produto->preco, 2, ',', '.') ?> €</td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p class="text-muted">Esta categoria ainda não tem produtos associados.</p>
    <?php endif; ?>
</div>
