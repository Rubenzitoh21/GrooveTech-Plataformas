<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Faturas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="faturas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'data')->textInput() ?>

    <?= $form->field($model, 'valortotal')->textInput() ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

<!--    --><?php //= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'pagamentos_id')->label('Método de Pagamento')->dropDownList(
        ArrayHelper::map(\common\models\Pagamentos::find()->all(), 'id', 'metodopag'),
        ['prompt' => 'Selecione o Método de Pagamento']
    ) ?>

    <?= $form->field($model, 'expedicoes_id')->label('Método de Expedição')->dropDownList(
        ArrayHelper::map(\common\models\Expedicoes::find()->all(), 'id', 'metodoexp'),
        ['prompt' => 'Selecione o Método de Expedição']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
