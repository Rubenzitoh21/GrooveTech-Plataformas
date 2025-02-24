<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\UserProfile $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
<br>
<br>
<div class="user-profile-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model->user, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model->user, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'primeironome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apelido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigopostal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'localidade')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rua')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nif')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dtanasc')->textInput() ?>

    <?= $form->field($model, 'dtaregisto')->textInput() ?>

    <?= $form->field($model, 'telefone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'genero')->dropDownList([ 'M' => 'M', 'F' => 'F', ], ['prompt' => '']) ?>

<!--    --><?php //= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
