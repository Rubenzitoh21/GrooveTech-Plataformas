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


    <p>
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
</div>
