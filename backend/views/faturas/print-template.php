<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Faturas $model */
/** @var backend\models\Empresas $empresa */

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> Fatura </title>
    <meta name="robots" content="noindex,nofollow" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0;" />
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: 'Open Sans', sans-serif;
        }
        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 60px;
            height: 60px;
        }
        .header .invoice-number {
            font-size: 21px;
            color: #36409f;
            font-weight: bold;
            text-align: right;
        }
        .header .invoice-date {
            font-size: 14px;
            color: #5b5b5b;
            margin-top: 5px;
            text-align: right;
        }
        .billing-shipping {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .billing-shipping div {
            width: 48%;
            font-size: 12px;
            color: #5b5b5b;
        }
        .billing-shipping h4 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #36409f;
            font-weight: bold;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .order-details table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .order-details th, .order-details td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #e4e4e4;
        }
        .order-details th {
            color: #5b5b5b;
            font-weight: bold;
            font-size: 15px;
        }
        .totals-and-info {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            position: relative;
            background: #ffffff;
            padding: 30px;
            box-sizing: border-box;
        }
        .totals-and-info div {
            width: 48%;
        }
        .totals-and-info table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .totals-and-info td {
            padding: 5px 0;
            color: #5b5b5b;
        }
        .totals-and-info td:last-child {
            text-align: right;
        }
        h4 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #36409f;
            font-weight: bold;
        }
    </style>
    <script>
        window.onload = function () {
            window.print();
            window.onafterprint = function () {
                window.close();
            };
        };
    </script>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <img src="<?= Url::to('@web/images/logo_gt.png') ?>" alt="Logo">
        <div class="invoice-number">
            Fatura #<?= Html::encode($model->id) ?>
            <div class="invoice-date">
                <?= Html::encode(Yii::$app->formatter->asDate($model->data, 'php:d/m/Y')) ?>
            </div>
        </div>
    </div>


    <!-- Billing and Shipping -->
    <div class="billing-shipping">
        <div>
            <h4>Informações da Empresa</h4>
            <p>
                <?= $empresa->designacaosocial ?><br>
                <?= $empresa->rua?><br>
                <?= $empresa->codigopostal . ' ' . $empresa->localidade?><br>
                T: <?= $empresa->telefone ?><br>
                Cap. Social: <?= $empresa->capitalsocial ?><br>
                Nif: <?= $empresa->nif ?>
            </p>
        </div>
        <div>
            <h4>Informações de Faturamento</h4>
            <p>
                <?= Html::encode($model->user->userProfile->primeironome . ' ' . $model->user->userProfile->apelido) ?><br>
                <?= Html::encode($model->user->userProfile->rua) ?><br>
                <?= Html::encode($model->user->userProfile->codigopostal . ' ' . $model->user->userProfile->localidade) ?><br>
                T: <?= Html::encode($model->user->userProfile->telefone) ?>
            </p>
        </div>
    </div>

    <!-- Order Details -->
    <div class="order-details">
        <h4>Detalhes do Pedido</h4>
        <table>
            <thead>
            <tr>
                <th>Produto</th>
                <th>Preço (unitário)</th>
                <th style="text-align: center;">Quantidade</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->linhasFaturas as $linha): ?>
                <tr>
                    <td><?= Html::encode($linha->produtos->nome) ?></td>
                    <td><?= number_format($linha->preco_venda, 2, ',', '.') ?>€</td>
                    <td style="text-align: center;"><?= Html::encode($linha->quantidade) ?></td>
                    <td style="text-align: right;"><?= number_format($linha->subtotal, 2, ',', '.') ?>€</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Info and Totals -->
    <div class="totals-and-info">
        <div>
            <h4>Informações de Expedição & Pagamento</h4>
            <table>
                <tr>
                    <td>Método de Expedição:</td>
                    <td><?= Html::encode($model->expedicoes->metodoexp) ?></td>
                </tr>
                <tr>
                    <td>Método de Pagamento:</td>
                    <td><?= Html::encode($model->pagamentos->metodopag) ?></td>
                </tr>
            </table>
        </div>
        <div>
            <h4>Resumo do Pedido</h4>
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td><?= number_format($model->valortotal - $model->linhasFaturas[0]->valor_iva, 2, ',', '.') ?>€</td>
                </tr>
                <tr>
                    <td>Portes de Envio:</td>
                    <td>0,00€</td>
                </tr>
                <tr>
                    <td>Iva:</td>
                    <td><?= number_format($model->linhasFaturas[0]->valor_iva, 2, ',', '.') ?>€</td>
                </tr>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td><strong><?= number_format($model->valortotal, 2, ',', '.') ?>€</strong></td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
