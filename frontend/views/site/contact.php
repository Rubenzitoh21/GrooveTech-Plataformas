<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ContactForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contactos';
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Se tiver dúvidas ou questões, por favor preencha o formulário abaixo para nos contactar. Obrigado.
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label('Nome') ?>

            <?= $form->field($model, 'email')->label('E-Mail') ?>

            <?= $form->field($model, 'subject')->label('Assunto') ?>

            <?= $form->field($model, 'body')->textarea(['rows' => 6])->label('Mensagem') ?>

            <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ])->label('Verificação (introduza a código abaixo)') ?>

            <div class="form-group">
                <?= Html::submitButton('Submeter', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

