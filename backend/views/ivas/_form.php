<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Ivas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ivas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'percentagem')->textInput() ?>

    <?= $form->field($model, 'descricao')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="form-label" for="vigor-toggle">Vigor</label>
        <br>
        <?= Html::hiddenInput('Ivas[vigor]', 0) ?>
        <?= Html::checkbox('Ivas[vigor]', $model->vigor, [
            'id' => 'vigor-toggle',
            'value' => 1,
            'data-toggle' => 'toggle',
            'data-on' => 'Em Vigor',
            'data-off' => 'Inativo',
            'data-onstyle' => 'primary',
            'data-offstyle' => 'danger',
        ]) ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
