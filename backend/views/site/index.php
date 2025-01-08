<?php

use yii\helpers\Html;

$this->title = 'Início';
$this->params['breadcrumbs'] = [['label' => "$this->title"]];
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4">Estatísticas Gerais</h1>
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
        <div class="col-12">
            <h1 class="display-4">Estatísticas Anuais</h1>
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
    <div class="row">
        <div class="col-12 text-center">
            <a href="<?= \yii\helpers\Url::to(['index', 'year' => $prevYear]) ?>" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> <?= $prevYear ?>
            </a>
            <span class="mx-3 h4"><?= $currentYear ?></span>
            <a href="<?= \yii\helpers\Url::to(['index', 'year' => $nextYear]) ?>" class="btn btn-primary">
                <?= $nextYear ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

</div>
<?php if (!empty($novosClientes)): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <?php foreach ($novosClientes as $user): ?>
            <div class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body">
                        Novo cliente registado: <?= Html::encode("{$user['primeironome']} {$user['apelido']}") ?>
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php if (!empty($novasCompras)): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <?php foreach ($novasCompras as $compra): ?>
            <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body">
                        Nova compra no valor de: <?= number_format($compra->valortotal, 2, ',', '.') ?> €.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
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
     // Notificações de novos clientes e compras
    var toastEl = document.querySelector('.toast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
        
        document.addEventListener('DOMContentLoaded', function () {
    const updateCharts = (url, chartId, yearSpanId) => {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const chart = Chart.getChart(chartId);
                chart.data.datasets[0].data = data.values;
                chart.update();

                // Atualizar o ano no elemento visual
                document.getElementById(yearSpanId).textContent = data.year;
            })
            .catch(error => console.error('Erro ao atualizar gráfico:', error));
    };
    
    // Atualizar gráficos segundo o ano selecionado
    document.getElementById('prev-year-faturamento').addEventListener('click', () => {
        const currentYear = parseInt(document.getElementById('current-year-faturamento').textContent, 10);
        const prevYear = currentYear - 1;
        const url = `/index.php?r=site&year=${prevYear}`;
        updateCharts(url, 'faturamento', 'current-year-faturamento');
    });

    document.getElementById('next-year-faturamento').addEventListener('click', () => {
        const currentYear = parseInt(document.getElementById('current-year-faturamento').textContent, 10);
        const nextYear = currentYear + 1;
        const url = `/index.php?r=site&year=${nextYear}`;
        updateCharts(url, 'faturamento', 'current-year-faturamento');
    });

    document.getElementById('prev-year-vendas').addEventListener('click', () => {
        const currentYear = parseInt(document.getElementById('current-year-vendas').textContent, 10);
        const prevYear = currentYear - 1;
        const url = `/index.php?r=site&year=${prevYear}`;
        updateCharts(url, 'vendas', 'current-year-vendas');
    });

    document.getElementById('next-year-vendas').addEventListener('click', () => {
        const currentYear = parseInt(document.getElementById('current-year-vendas').textContent, 10);
        const nextYear = currentYear + 1;
        const url = `/index.php?r=site&year=${nextYear}`;
        updateCharts(url, 'vendas', 'current-year-vendas');
    });
});

JS);
?>