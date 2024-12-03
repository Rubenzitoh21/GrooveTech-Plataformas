<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Expedicoes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="expedicoes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,'metodoexp',)->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
