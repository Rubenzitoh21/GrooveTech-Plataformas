<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\LinhasFaturas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="linhas-faturas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'quantidade')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preco_venda')->textInput() ?>

    <?= $form->field($model, 'valor_iva')->textInput() ?>

    <?= $form->field($model, 'subtotal')->textInput() ?>

    <?= $form->field($model, 'faturas_id')->textInput() ?>

    <?= $form->field($model, 'avaliacoes_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
