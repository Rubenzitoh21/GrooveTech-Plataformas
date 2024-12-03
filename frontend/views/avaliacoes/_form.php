<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Avaliacoes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="avaliacoes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'comentario')->textarea([
        'maxlength' => true,
        'rows' => 5,
        'placeholder' => 'Escreva seu comentário sobre o produto...',
    ]) ?>

    <div class="form-group">
        <label for="rating">Avaliação:</label>
        <div id="star-rating" class="star-rating">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="fa fa-star star" data-value="<?= $i ?>"></span>
            <?php endfor; ?>
        </div>
        <?= $form->field($model, 'rating')->hiddenInput(['id' => 'rating-value'])->label(false) ?>
    </div>

    <?= $form->field($model, 'dtarating')->hiddenInput(['value' => date('Y-m-d H:i:s')])->label(false) ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <?= $form->field($model, 'linhas_faturas_id')->hiddenInput(['value' => $linhaFaturaId])->label(false) ?>
    <br>
    <div class="form-group">
        <?= Html::submitButton('Enviar Avaliação', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
