<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\ImagensSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="imagens-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fileName') ?>

    <?= $form->field($model->produto->nome, 'nome') ?>

    <div class="form-group">
        <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
