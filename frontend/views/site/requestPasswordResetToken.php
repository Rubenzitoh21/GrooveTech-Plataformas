<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\PasswordResetRequestForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Reposição da palavra-passe';
?>
<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Insira o seu E-mail. Um link para repor a sua palavra-passe será enviado.</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
