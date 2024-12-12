<?php

use yii\helpers\Html;

$this->title = 'Início';
$this->params['breadcrumbs'] = [['label' => "$this->title"]];
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4">Estatísticas</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => number_format($totalFaturado, 2, ',', '.') . ' €',
                'text' => 'Total Faturado',
                'icon' => 'fas fa-euro-sign',
                'theme' => 'success',
                'linkText' => 'Ver Faturas',
                'linkUrl' => ['faturas/index']
            ]) ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => $totalFaturas,
                'text' => 'Total de Vendas',
                'icon' => 'fas fa-file-invoice',
                'theme' => 'info',
                'linkText' => 'Ver Faturas',
                'linkUrl' => ['faturas/index']
            ]) ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => $totalClientes,
                'text' => 'Clientes Registados',
                'icon' => 'fas fa-users',
                'theme' => 'gradient-warning',
                'linkText' => 'Ver Clientes',
                'linkUrl' => ['user-profile/index']
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total Faturado por Mês</h3>
                </div>
                <div class="card-body">
                    <canvas id="faturamento" style="height: 300px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quantidade de Vendas por Mês</h3>
                </div>
                <div class="card-body">
                    <canvas id="vendas" style="height: 300px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs(<<<JS
    // Gráfico de Quantidade de Vendas por Mês
    var ctx1 = document.getElementById('vendas').getContext('2d');
    var salesChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            datasets: [{
                label: 'Vendas',
                data: [
                    {$meses[1]}, {$meses[2]}, {$meses[3]}, {$meses[4]},
                    {$meses[5]}, {$meses[6]}, {$meses[7]}, {$meses[8]},
                    {$meses[9]}, {$meses[10]}, {$meses[11]}, {$meses[12]}
                ],
                backgroundColor: '#17a2b8',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

    // Gráfico de Total Faturado por Mês
    var ctx2 = document.getElementById('faturamento').getContext('2d');
    var revenueChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            datasets: [{
                label: 'Total Faturado (€)',
                data: [
                    {$faturado[1]}, {$faturado[2]}, {$faturado[3]}, {$faturado[4]},
                    {$faturado[5]}, {$faturado[6]}, {$faturado[7]}, {$faturado[8]},
                    {$faturado[9]}, {$faturado[10]}, {$faturado[11]}, {$faturado[12]}
                ],
                backgroundColor: '#28a745',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
JS);
?>