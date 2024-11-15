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
    <a href="../" class="brand-link">
        <?= Html::img('@web/img/logo_gt.png',
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
//                    ['label' => 'Yii2 PROVIDED', 'header' => true],
//                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    ['label' => 'Gestão de Dados', 'header' => true],
//                    [
//                        'label' => 'Gestão de Dados', 'icon' => 'fas fa-file',
//                        'items' => [
                            ['label' => 'Gerir Trabalhadores', 'icon' => 'users', 'url' => ['/user/index'], 'visible' => ($userRole == 'admin')],
                            ['label' => 'Gerir Clientes', 'icon' => 'users', 'url' => ['clientes/index'], 'visible' => ($userRole == 'admin')],
//                            ['label' => 'IVAS', 'icon' => 'fa-solid fa-percent', 'url' => ['/ivas/index']],
//                            ['label' => 'Avaliações', 'icon' => 'fa-solid fa-star', 'url' => ['/avaliacoes/index']],
//                        ],
//                    ],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>