<?php

use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var common\models\User $userData */
/** @var common\models\UserProfile $userDataAdditional */
/** @var common\models\User $passwordModel */
/** @var bool $mode */

$this->title = 'Perfil';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="container">
        <div style="display: flex; align-items: center;">
            <h1 class="mb-4 mt-5" style="flex: 0.99;">Perfil</h1>
            <?= Html::a('Produtos Comprados', ['avaliacoes/index'], ['class' => 'btn btn-success']) ?>
            <h1 class="mb-4 mt-5" style="flex: 0.01;"></h1>
            <?= Html::a('As Minhas Faturas', ['faturas/index'], ['class' => 'btn btn-success']) ?>
        </div>
        <div id="dados-pessoais" class="row g-4">
            <div class="col-lg-12">
                <div class="row g-4 justify-content-center">
                    <!-- Profile Section -->
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-body d-flex align-items-center position-relative">
                                <div class="col-md-7 ps-md-4">
                                    <h2>Dados Pessoais</h2>
                                    <hr>

                                    <?php if ($mode === 'data'): ?>
                                        <?php
                                        $form = ActiveForm::begin([
                                            'options' => ['class' => 'form'],
                                            'action' => ['perfil/index', 'mode' => 'data'],
                                            'method' => 'post',
                                        ]);
                                        ?>
                                        <?= $form->field($userData, 'username')->textInput(['value' => $userData->username, 'placeholder' => 'Insira o seu nome de utilizador'])->label('Nome de Utilizador') ?>
                                        <br>
                                        <?= $form->field($userDataAdditional, 'primeironome')->textInput(['value' => $userDataAdditional->primeironome, 'placeholder' => 'Insira o seu primeiro nome'])->label('Nome') ?>
                                        <br>
                                        <?= $form->field($userDataAdditional, 'apelido')->textInput(['value' => $userDataAdditional->apelido, 'placeholder' => 'Insira o seu apelido'])->label('Apelido') ?>
                                        <br>
                                        <?= $form->field($userData, 'email')->textInput(['value' => $userData->email, 'placeholder' => 'Insira o seu email'])->label('Email') ?>
                                        <br>
                                        <?= $form->field($userDataAdditional, 'telefone')->textInput(['value' => $userDataAdditional->telefone, 'placeholder' => 'Insira o seu número de telemóvel'])->label('Telemóvel') ?>
                                        <br>
                                        <?= $form->field($userDataAdditional, 'nif')->textInput(['value' => $userDataAdditional->nif, 'placeholder' => 'Insira o seu NIF'])->label('NIF') ?>
                                        <br>
                                        <?= $form->field($userDataAdditional, 'genero')->dropDownList(
                                        [
                                            "M" => 'Masculino',
                                            "F" => 'Feminino',
                                        ],
                                        ['prompt' => 'Selecione o seu género']
                                    )->label('Género') ?>
                                        <br>
                                        <?= $form->field($userDataAdditional, 'dtanasc')->label('Data de Nascimento')->textInput([
                                            'id' => 'datepicker',
                                            'placeholder' => 'Selecione a sua data de nascimento'
                                        ]);

                                        $this->registerJs("
                                            $('#datepicker').datepicker({
                                                format: 'yyyy-mm-dd',
                                                autoclose: true,
                                                todayHighlight: true,
                                                endDate: '-16y',
                                                language: 'pt'
                                            });
                                        ");
                                        ?>
                                        <br>
                                        <?= Html::submitButton('Atualizar', ['class' => 'btn btn-success']) ?>
                                        <?php if ($mode === 'data'): ?>
                                            <?= Html::a('Cancelar', ['perfil/index', 'mode' => null], ['class' => 'btn btn-secondary']) ?>
                                        <?php endif; ?>
                                        <?php ActiveForm::end(); ?>
                                    <?php else: ?>
                                        <p><b>Nome de Utilizador:</b> <?= Html::encode($userData->username) ?></p>
                                        <p><b>Nome:</b> <?= Html::encode($userDataAdditional->primeironome) ?></p>
                                        <p><b>Apelido:</b> <?= Html::encode($userDataAdditional->apelido) ?></p>
                                        <p><b>Email:</b> <?= Html::encode($userData->email) ?></p>
                                        <p><b>Telemóvel:</b> <?= Html::encode($userDataAdditional->telefone) ?></p>
                                        <p><b>NIF:</b> <?= Html::encode($userDataAdditional->nif) ?></p>
                                        <?php
                                        $generoLabel = ($userDataAdditional->genero === 'M') ? 'Masculino' : 'Feminino';
                                        ?>
                                        <p><b>Género:</b> <?= Html::encode($generoLabel) ?></p>
                                        <p><b>Data de Nascimento:</b> <?= Html::encode($userDataAdditional->dtanasc) ?>
                                        </p>
                                        <?php if ($mode === null): ?>
                                            <?= Html::a('Editar Dados', ['perfil/index', 'mode' => 'data','#' => 'dados-pessoais'], ['class' => 'btn btn-success']) ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Profile Section -->
                </div>
            </div>
        </div>
        <br>
        <div id="morada" class="row g-4">
            <div class="col-lg-12">
                <div class="row g-4 justify-content-center">
                    <!-- Profile Section -->
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-body d-flex align-items-center position-relative">
                                <div class="col-md-7 ps-md-4">
                                    <h2>Dados De Morada</h2>
                                    <hr>

                                    <?php if ($mode === 'morada'): ?>
                                        <?php
                                        $form = ActiveForm::begin([
                                            'id' => 'profile-form-user-morada-data',
                                            'options' => ['class' => 'form'],
                                            'action' => ['perfil/index', 'mode' => 'morada'], // Update the action attribute
                                            'method' => 'post', // Set the method to post
                                        ]);
                                        ?>
                                        <?= $form->field($userDataAdditional, 'rua')->textInput(['value' => $userDataAdditional->rua, 'placeholder' => 'Insira a sua rua'])->label('Rua') ?>
                                        <br>
                                        <?= $form->field($userDataAdditional, 'localidade')->textInput(['value' => $userDataAdditional->localidade, 'placeholder' => 'Insira a sua localidade'])->label('Localidade') ?>
                                        <br>
                                        <?= $form->field($userDataAdditional, 'codigopostal')->textInput(['value' => $userDataAdditional->codigopostal, 'placeholder' => 'Insira o seu código postal'])->label('Código Postal') ?>
                                        <br>
                                        <?= Html::submitButton('Atualizar', ['class' => 'btn btn-success']) ?>
                                        <?php if ($mode == 'morada'): ?>
                                            <?= Html::a('Cancelar', ['perfil/index', 'mode' => null], ['class' => 'btn btn-secondary']) ?>
                                        <?php endif; ?>
                                        <?php ActiveForm::end(); ?>
                                    <?php else: ?>
                                        <p><b>Rua:</b> <?= Html::encode($userDataAdditional->rua) ?></p>
                                        <p><b>Localidade:</b> <?= Html::encode($userDataAdditional->localidade) ?></p>
                                        <p><b>Código Postal:</b> <?= Html::encode($userDataAdditional->codigopostal) ?>
                                        </p>
                                        <?php if ($mode === null): ?>
                                            <?= Html::a('Editar Dados', ['perfil/index', 'mode' => 'morada', '#' => 'morada'], ['class' => 'btn btn-success']) ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Profile Section -->
                </div>
            </div>
        </div>
        <br>
        <div id="password" class="row g-4">
            <div class="col-lg-12">
                <div class="row g-4 justify-content-center">
                    <!-- Password Section -->
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-body d-flex align-items-center position-relative">
                                <div class="col-md-7 ps-md-4">
                                    <h2>Alterar Password</h2>
                                    <hr>

                                    <?php if ($mode === 'password'): ?>
                                        <?php
                                        $formPassword = ActiveForm::begin([
                                            'id' => 'password-form',
                                            'options' => ['class' => 'form'],
                                            'action' => ['perfil/index', 'mode' => 'password'],
                                            'method' => 'post',
                                        ]);
                                        ?>

                                        <?= $formPassword->field($passwordModel, 'currentPassword')->passwordInput(['placeholder' => 'Password Atual'])->label('Password Atual') ?>
                                        <br>
                                        <?= $formPassword->field($passwordModel, 'newPassword')->passwordInput(['placeholder' => 'Nova Password'])->label('Nova Password') ?>
                                        <br>
                                        <?= $formPassword->field($passwordModel, 'confirmPassword')->passwordInput(['placeholder' => 'Confirmar Nova Password'])->label('Confirmar Nova Password') ?>
                                        <br>
                                        <div class="d-flex gap-2">
                                            <?= Html::submitButton('Alterar Password', ['class' => 'btn btn-success']) ?>
                                            <?= Html::a('Cancelar', ['perfil/index', 'mode' => null], ['class' => 'btn btn-secondary']) ?>
                                        </div>

                                        <?php ActiveForm::end(); ?>
                                    <?php endif; ?>

                                    <?php if ($mode === null): ?>
                                        <?= Html::a('Alterar Password', ['perfil/index', 'mode' => 'password', '#' => 'password'], ['class' => 'btn btn-success']) ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Password Section -->
                </div>
            </div>
        </div>
    </div>
    <?php
    $this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css');
    $this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js', ['depends' => [yii\web\JqueryAsset::class]]);
    $this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/locales/bootstrap-datepicker.pt.min.js', ['depends' => [yii\web\JqueryAsset::class]]);
    ?>
</div>
