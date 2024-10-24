<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProdutosCarrinhosSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="produtos-carrinhos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'quantidade') ?>

    <?= $form->field($model, 'preco_venda') ?>

    <?= $form->field($model, 'valor_iva') ?>

    <?= $form->field($model, 'subtotal') ?>

    <?php // echo $form->field($model, 'produtos_id') ?>

    <?php // echo $form->field($model, 'carrinhos_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
