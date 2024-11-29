<?php

use common\models\Produtos;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Imagens $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="imagens-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<!---->
<!--    --><?php //= $form->field($model, 'fileName')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?php //= $form->field($model, 'produto_id')->textInput() ?>
<!---->
<!--    <div class="form-group">-->
<!--        --><?php //= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
<!--    </div>-->
    <?= $form->field($model, 'produto_id')->label('Produto')->dropDownList(
        ArrayHelper::map(Produtos::find()->all(), 'id', 'nome'),
        [
            'prompt' => 'Selecione o produto para associar à imagem',
            'disabled' => true,
        ]
    ) ?>
    <?= $form->field($model, 'imageFiles[]')->label('Selecionar Imagem')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
