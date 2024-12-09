<?php

/** @var yii\web\View$this  */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ResetPasswordForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Reenviar E-mail de verificação';
?>
<div class="site-resend-verification-email">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Insira o seu E-mail. Um link para repor a sua palavra-passe será enviado.</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
