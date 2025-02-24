<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CategoriasProdutos $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
<br>
<br>
<div class="categorias-produtos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'obs')->label('Observações')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
