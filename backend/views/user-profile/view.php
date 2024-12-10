<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\UserProfile $model */
/** @var common\models\faturas $faturas */


$this->title = $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'User Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-profile-view">


    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem a certeza que pretende eliminar esta conta?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'primeironome',
            'apelido',
            'codigopostal',
            'localidade',
            'rua',
            'nif',
            'dtanasc',
            'dtaregisto',
            'telefone',
            'genero',
//            'user_id',
        ],
    ]) ?>

    <br>
    <h3>Faturas</h3>
    <table class="table table-bordered table-striped">
        <tr>
            <th>Id</th>
            <th>Data</th>
            <th>Valor</th>
            <th>Status</th>
            <th></th>
        </tr>
        <?php foreach ($model->user->faturas as $fatura): ?>
            <tr>
                <td><?= $fatura->id ?></td>
                <td><?= $fatura->data ?></td>
                <td><?= number_format($fatura->valortotal, 2, ',', '.') ?> €</td>
                <td><?= $fatura->status ?></td>
                <td>
                    <button class="btn btn-success btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#fatura-<?= $fatura->id ?>" aria-expanded="false" aria-controls="fatura-<?= $fatura->id ?>">
                        Ver Linhas
                    </button>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <div class="collapse" id="fatura-<?= $fatura->id ?>">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Preço Unitáraio</th>
                                <th>Iva</th>
                                <th>Sub-Total</th>
                                <th>Total</th>
                            </tr>
                            <?php foreach ($fatura->linhasFaturas as $linhaFatura): ?>
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
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

