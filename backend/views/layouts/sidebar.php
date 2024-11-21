<?php

/* @var $this \yii\web\View */
/* @var $content string */

/* @var $assetDir string */

use backend\assets\AppAsset;
use yii\bootstrap5\Html;

AppAsset::register($this);
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Yii::getAlias('../') ?>" class="brand-link" >
        <?= Html::img('@web/images/logo_gt.png',
            [
                'alt'=>'Groove Tech Logo',
                'class'=>'brand-image img-circle elevation-3',
            ]) ?>
        <span class="brand-text font-weight-light">Groove Tech</span>
    </a>

    <!--icon de terminar a sessão -> fas fa-sign-out-alt-->
    <a class="brand-link">
        <?php
        if (!Yii::$app->user->isGuest) {
            echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
                . Html::submitButton(
                    '<i class="fas fa-sign-out-alt" style="opacity: .8"></i> Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout font-weight-light']
                )
                . Html::endForm();
        }
        ?>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <?php
            $userRole = Yii::$app->user->can('admin') ? 'admin' : 'gestor';
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    ['label' => 'Perfil', 'header' => true],
                        ['label' => 'Perfil', 'icon' => 'fas fa-id-card', 'url' => ['/perfil/index'], 'visible' => ($userRole == 'admin' || $userRole == 'gestor')],
                    ['label' => 'Empresa', 'header' => true, 'visible' => ($userRole == 'admin')],
                        ['label' => 'Dados da Empresa', 'icon' => 'fa-solid fa-briefcase', 'url' => ['/empresas/index'], 'visible' => ($userRole == 'admin')],
                    ['label' => 'Gestão de Utilizadores', 'header' => true],
                        ['label' => 'Trabalhadores', 'icon' => 'fa-solid fa-user-tie', 'url' => ['/user/index'], 'visible' => ($userRole == 'admin')],
                        ['label' => 'Clientes', 'icon' => 'users', 'url' => ['/user-profile/index'], 'visible' => ($userRole == 'admin' || $userRole == 'gestor')],
                    ['label' => 'Gestão de Dados', 'header' => true],
                        ['label' => 'Faturas', 'icon' => 'fas fa-file-invoice', 'url' => ['/faturas/index'], 'visible' => ($userRole == 'admin' || $userRole == 'gestor')],
                        ['label' => 'Expediçoes', 'icon' => 'fas fa-plane', 'url' => ['/expedicoes/index'], 'visible' => ($userRole == 'admin' || $userRole == 'gestor')],
                        ['label' => 'Avaliações', 'icon' => 'fa-solid fa-star', 'url' => ['/avaliacoes/index'], 'visible' => ($userRole == 'admin' || $userRole == 'gestor')],
                        ['label' => 'Pagamentos', 'icon' => 'fa-solid fa-credit-card', 'url' => ['/pagamentos/index'], 'visible' => ($userRole == 'admin' || $userRole == 'gestor')],
                    ['label' => 'Gestão de Produtos', 'header' => true],
                        ['label' => 'Categorias', 'icon' => 'fa-solid fa-box', 'url' => ['/categorias-produtos/index'], 'visible' => ($userRole == 'admin' || $userRole == 'gestor')],
                        ['label' => 'Produtos', 'icon' => 'fa-solid fa-tag', 'url' => ['/produtos/index'], 'visible' => ($userRole == 'admin' || $userRole == 'gestor')],
                        ['label' => 'Ivas', 'icon' => 'fa-solid fa-percent', 'url' => ['/ivas/index'], 'visible' => ($userRole == 'admin' || $userRole == 'gestor')],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>