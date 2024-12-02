<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Carrinhos $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="carrinhos-form">

    <?php $form = ActiveForm::begin(); ?>



<!--    --><?php //= $form->field($model, 'pagamentos_id')->label('Método de Pagamento')->dropDownList(
//        ArrayHelper::map(\common\models\Pagamentos::find()->all(), 'id', 'metodopag'),
//        ['prompt' => 'Selecione o Método de Pagamento']
//    ) ?>
<!--    <br>-->
<!--    --><?php //= $form->field($model, 'expedicoes_id')->label('Método de Envio')->dropDownList(
//        ArrayHelper::map(\common\models\Expedicoes::find()->all(), 'id', 'metodoexp'),
//        ['prompt' => 'Selecione o Método de Envio']
//    ) ?>


    <div class="form-group mt-3">
        <?= Html::submitButton('Encomendar', ['class' => 'btn border-secondary py-3 px-4 text-uppercase w-100 text-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
