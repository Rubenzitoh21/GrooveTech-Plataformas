<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Iniciar Sessão';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Preencha todos os campos para iniciar sessão.</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="my-1 mx-0" style="color:#999;">
                    Esqueceu-se da sua password? <?= Html::a('Repor', ['site/request-password-reset']) ?>.
                </div>
                <br>
                <div class="form-group">
                    <?= Html::submitButton('Iniciar Sessão', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-5 offset-lg-1 ">
            <div>
                <h3>Ainda não tem uma conta?</h3>
                <p>Crie já uma conta para aproveitar todos os nossos serviços!</p>
                <?= Html::a('Criar Conta', ['site/signup'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>
