<?php

use common\models\Expedicoes;
use common\models\Pagamentos;
use yii\helpers\Url;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Faturas $model */
/** @var backend\models\Empresas $empresa */
/** @var common\models\UserProfile $profile */

$this->title = $model->id;
\yii\web\YiiAsset::register($this);
?>
<div class="faturas-view">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="<?= Url::to(['faturas/index']) ?>">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="invoice-title">
                            <h4 class="float-end font-size-15">Fatura #<?= $model->id ?><span class="badge bg-success font-size-12 ms-2"><?= $model->status ?></span></h4>
                            <div class="mb-4">
                                <h2 class="mb-1 text-muted">GrooveTech</h2>
                            </div>
                            <div class="text-muted">
                                <h5 class="font-size-16 mb-3">De:</h5>
                                <h5 class="font-size-15 mb-2"><?= $empresa->designacaosocial ?></h5>
                                <p class="mb-1"><?= $empresa->email ?></p>
                                <p class="mb-1"><?= $empresa->rua?></p>
                                <p class="mb-1"><?= $empresa->codigopostal . ' ' . $empresa->localidade?> </p>
                                <p class="mb-1"><?= $empresa->telefone ?></p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="text-muted">
                                    <h5 class="font-size-16 mb-3">Para:</h5>
                                    <h5 class="font-size-15 mb-2"><?= $profile->primeironome . ' ' . $profile->apelido ?></h5>
                                    <p class="mb-1"><?= $profile->rua ?></p>
                                    <p class="mb-1"><?= $profile->codigopostal. ' ' . $profile->localidade ?></p>
                                    <p class="mb-1"><?= Yii::$app->user->identity->email ?></p>
                                    <p><?= $profile->telefone ?></p>
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-sm-6">
                                <div class="text-muted text-sm-end">
                                    <div class="mt-4">
                                        <h5 class="font-size-15 mb-1">Data de faturação:</h5>
                                        <p><?= $model->data ?></p>
                                    </div>
                                    <div class="mt-4">
                                        <h5 class="font-size-15 mb-1">Método de Pagamento:</h5>
                                        <p><?= $model->pagamentos->metodopag ?></p>
                                    </div>
                                    <div class="mt-4">
                                        <h5 class="font-size-15 mb-1">Método de Expedição:</h5>
                                        <p><?= $model->expedicoes->metodoexp ?></p>
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <br>
                        <br>
                        <!-- end row -->
                        <div class="py-2">
                            <h5 class="font-size-15">Resumo da compra</h5>

                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-centered mb-0">
                                    <thead>
                                    <tr>
                                        <th style="width: 30px;">#</th>
                                        <th>Produto</th>
                                        <th>Preço</th>
                                        <th>Quantidade</th>
                                        <th class="text-end" style="width: 120px;">Sub-Total</th>
                                    </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                    <?php
                                    $linhaTotal = 0;
                                    $totalIva = 0;
                                    $subTotal = 0;
                                    foreach ($model->linhasFaturas as $index => $linhaFatura):
                                        $linhaTotal = $linhaFatura->produtos->preco * $linhaFatura->quantidade;
                                        $totalIva += $linhaFatura->valor_iva * $linhaFatura->quantidade;
                                        $subTotal += $linhaTotal;
                                        ?>
                                        <tr>
                                            <th scope="row"><?= $index + 1 ?></th>
                                            <td>
                                                <div>
                                                    <h5 class="text-truncate font-size-14 mb-1"><?= Html::encode($linhaFatura->produtos->nome) ?></h5>
                                                    <p class="text-muted mb-0"><?= Html::encode($linhaFatura->produtos->descricao ?? '') ?></p>
                                                </div>
                                            </td>
                                            <td><?= number_format($linhaFatura->preco_venda, 2, ',', '.') ?>€</td>
                                            <td><?= Html::encode($linhaFatura->quantidade) ?></td>
                                            <td class="text-end"><?= number_format($linhaTotal, 2, ',', '.') ?>€</td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">Sub-Total</th>
                                        <td class="text-end"><?= number_format($subTotal- $totalIva, 2, ',', '.') ?>€</td>
                                    </tr>
                                    <!-- end tr -->
                                    <tr>
                                        <th scope="row" colspan="4" class="border-0 text-end">
                                            Portes de Envio :</th>
                                        <td class="border-0 text-end">0,00€</td>
                                    </tr>
                                    <!-- end tr -->
                                    <tr>
                                        <th scope="row" colspan="4" class="border-0 text-end">
                                            Iva:</th>
                                        <td class="border-0 text-end"><?= number_format($totalIva, 2, ',', '.') ?>€</td>
                                    </tr>
                                    <!-- end tr -->
                                    <tr>
                                        <th scope="row" colspan="4" class="border-0 text-end">Total:</th>
                                        <td class="border-0 text-end"><h4 class="m-0 fw-semibold"><?= number_format($model->valortotal, 2, ',', '.') ?>€</h4></td>
                                    </tr>
                                    <!-- end tr -->
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div><!-- end table responsive -->
                            <?php if ($model->status === 'Pago'): ?>
                                <div class="d-print-none mt-4">
                                    <div class="float-end">
                                        <?= Html::a('<i class="fa fa-print"></i> Imprimir Fatura', ['faturas/print', 'id' => $model->id], [
                                            'class' => 'btn btn-success',
                                            'target' => '_blank',
                                            'rel' => 'noopener noreferrer',
                                        ]) ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div><!-- end col -->
        </div>
    </div>
</div>
