<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/** @var yii\web\View $this */
/** @var common\models\Carrinhos $model */
/** @var common\models\UserProfile $userData */
/** @var common\models\ProdutosCarrinhos $produtosCarrinhos */
/** @var common\models\Produtos $produtos */
/** @var common\models\Pagamentos $pagamento */


$this->title = 'Checkout';
?>
<div class="carrinhos-update container-fluid">
    <div class="container-fluid py-5">
        <a class="btn btn-success" href="javascript:window.history.back();">
            <i class="fa fa-arrow-left"></i> Voltar
        </a>
        <div class="container py-5">
            <?php if (Yii::$app->session->hasFlash('success')): ?>
            <?php elseif (Yii::$app->session->hasFlash('error')): ?>
            <?php endif; ?>
            <h1 class="mb-4">Detalhes de Faturação</h1>

            <div class="row g-4">
                <div class="col-md-12 col-lg-6 col-xl-6">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'profile-form-user-data',
                        'options' => ['class' => 'form'],
                        'action' => ['carrinhos/userdataupdate', 'id' => $model->id],
                        // Update the action attribute
                        'method' => 'post', // Set the method to post
                    ]);
                    ?>

                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-item w-100">
                                <?= $form->field($userData,
                                    'primeironome')->textInput(['value' => $userData->primeironome,
                                    'placeholder' => 'Insira o seu nome'])
                                    ->label('Nome') ?>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-6">
                            <div class="form-item w-100">
                                <?= $form->field($userData,
                                    'apelido')->textInput(['value' => $userData->apelido,
                                    'placeholder' => 'Insira o seu apelido'])
                                    ->label('Apelido') ?>

                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="form-item">
                        <?= $form->field($userData,
                            'rua')->textInput(['value' => $userData->rua, 'placeholder' => 'Insira a sua Morada'])
                            ->label('Morada') ?>

                    </div>
                    <br>
                    <div class="form-item">
                        <?= $form->field($userData,
                            'codigopostal')->textInput(['value' => $userData->codigopostal,
                            'placeholder' => 'Insira o Código Postal'])
                            ->label('Código Postal') ?>

                    </div>
                    <br>
                    <div class="form-item">
                        <?= $form->field($userData,
                            'localidade')->textInput(['value' => $userData->localidade,
                            'placeholder' => 'Insira o sua Localidade'])
                            ->label('Localidade') ?>

                    </div>
                    <br>
                    <div class="form-item">
                        <?= $form->field($userData,
                            'telefone')->textInput(['value' => $userData->telefone,
                            'placeholder' => 'Insira o seu número de telemóvel'])
                            ->label('Telemóvel') ?>

                    </div>
                    <br>
                    <div class="form-item">
                        <?= $form->field($userData,
                            'nif')->textInput(['value' => $userData->nif, 'placeholder' => 'Insira o seu NIF'])
                            ->label('NIF') ?>
                    </div>
                    <br>
                    <div class="form-item mt-3">
                        <?= Html::submitButton('Atualizar', ['class' => 'btn btn-success']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-md-12 col-lg-6 col-xl-6">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Produto</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Preço</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <?php
                                $iva = 0;
                                foreach ($model->produtosCarrinhos as $produtoCarrinho):
                                $iva += $produtoCarrinho->valor_iva * $produtoCarrinho->quantidade;
                                if (!empty($produtoCarrinho->produtos->imagens)) : ?>

                                <div class="d-flex align-items-center mt-2">
                                    <td> <?= Html::img(
                                            Url::to('@web/images/' . $produtoCarrinho->produtos->imagens[0]->fileName),
                                            ['class' => 'img-fluid me-5'] + ['width' => '100px']
                                        ) ?></td>

                                    <?php else : ?>

                                        <td><?= Html::img(
                                                Url::to('@web/images/default.png'),
                                                ['class' => 'img-fluid me-5'] + ['width' => '100px']
                                            ) ?></td>

                                    <?php endif; ?>
                                </div>

                                <td class="py-5"><?= Html::encode($produtoCarrinho->produtos->nome) ?></td>
                                <td class="py-5"><?= number_format($produtoCarrinho->produtos->preco, 2, ',', '.') ?>€</td>
                                <td class="py-5"><?= Html::encode($produtoCarrinho->quantidade) ?></td>
                                <td class="py-5"><?= number_format($produtoCarrinho->produtos->preco *
                                        $produtoCarrinho->quantidade,
                                        2,
                                        ',',
                                        '.') ?>€
                                </td>
                            </tr>
                            <?php endforeach; ?>

                            <tr>
                                <th scope="row">
                                </th>
                                <td class="py-5">
                                    <p class="mb-0 text-dark text-uppercase py-3">Subtotal</p>
                                    <p class="mb-0 text-dark text-uppercase py-3">IVA</p>
                                </td>
                                <td class="py-5"></td>
                                <td class="py-5"></td>

                                <td class="py-5">
                                    <div class=" border-bottom border-top py-3">
                                        <p class="mb-0 text-dark"><?= number_format($model->valortotal - $iva,
                                                2,
                                                ',',
                                                '.') ?>€</p>
                                        <p class="mb-0 text-dark py-3"><?= number_format($iva, 2, ',', '.') ?>€</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                </th>
                                <td class="py-5">
                                    <p class="mb-0 text-dark text-uppercase py-3">TOTAL</p>
                                </td>
                                <td class="py-5"></td>
                                <td class="py-5"></td>
                                <td class="py-5">
                                    <div class="py-3 border-bottom border-top">
                                        <p class="mb-0 text-dark"><?= number_format($model->valortotal, 2, ',', '.') ?>€</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="py-5">
                                    <?= $this->render('_form', [
                                        'model' => $model,
                                        'fatura' => $fatura,
                                    ]) ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>








