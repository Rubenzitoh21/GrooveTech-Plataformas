<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\models\Carrinhos;
use backend\models\Empresas;
use common\models\CategoriasProdutos;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

$empresa = Empresas::findOne(1);
$categorias = CategoriasProdutos::find()->limit(6)->all();

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="icon" type="image/png" href="<?= Url::to('@web/images/logo_gt.png') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/css/templatemo.css') ?>">
<!--    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">-->
    <link rel="stylesheet" href="<?= Url::to('@web/css/fontawesome.min.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/css/custom.css') ?>">

</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light shadow">
    <div class="container d-flex justify-content-between align-items-center">

        <a class="navbar-brand text-success logo h1 align-self-center" href="<?= Url::to(['/site/index']) ?>">
            <img src="<?= Url::to('@web/images/logo_gt.png') ?>" alt="Logo" width="100px" >
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="align-self-center collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
            <div class="flex-fill">
                <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to(['/site/index']) ?>">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to(['/site/about']) ?>">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to(['/produtos/index']) ?>">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to(['/site/contact']) ?>">Contactos</a>
                    </li>
                </ul>
            </div>
            <div class="navbar-inner align-self-center d-flex">
                <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputMobileSearch" placeholder="Pesquisar...">
                        <div class="input-group-text">
                            <i class="fa fa-fw fa-search"></i>
                        </div>
                    </div>
                </div>
                <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal" data-bs-target="#templatemo_search">
                    <i class="fa fa-fw fa-search text-dark mr-2"></i>
                </a>
                <a class="nav-icon position-relative text-decoration-none" href="<?= Url::to(['/carrinhos/index']) ?>">
                    <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                    <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">
                        <?= Carrinhos::getTotalProdutosCarrinho() ?>
                    </span>
                </a>
                <a class="nav-icon position-relative text-decoration-none" href="<?= Yii::$app->user->isGuest ? Url::to(['/site/login']) : Url::to(['/perfil/index']) ?>">
                    <i class="fa fa-fw fa-user text-dark mr-3"></i>
                </a>
                <?php if (!Yii::$app->user->isGuest): ?>
                    <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'nav-icon position-relative text-decoration-none']) ?>
                    <?= Html::submitButton(
                        '<i class="fa fa-fw fa-sign-out-alt text-dark mr-3"></i>',
                        ['class' => 'btn btn-link p-0 text-decoration-none']
                    ) ?>
                    <?= Html::endForm() ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</nav>
<!-- Modal -->
<div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="w-100 pt-1 mb-5 text-right">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <?php $form = \yii\widgets\ActiveForm::begin([
            'action' => ['produtos/index'], // Altere para a rota correta
            'method' => 'get',
            'options' => ['class' => 'modal-content modal-body border-0 p-0']
        ]); ?>
        <div class="input-group mb-2">
            <?= Html::input('text', 'search', '', [
                'class' => 'form-control',
                'id' => 'inputModalSearch',
                'placeholder' => 'Pesquisar ...'
            ]) ?>
            <button type="submit" class="input-group-text bg-success text-light">
                <i class="fa fa-fw fa-search text-white"></i>
            </button>
        </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>


<!-- Main Content -->
<main role="main" >
    <div class="container">
        <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>
<!-- Start Footer -->
<footer class="bg-dark" id="tempaltemo_footer">
    <div class="container">
        <div class="row">

            <!-- Informações da Empresa -->
            <div class="col-md-4 pt-5">
                <h2 class="h2 text-success border-bottom pb-3 border-light"><?= Html::encode($empresa->designacaoSocial ?? 'Groove Tech') ?></h2>
                <ul class="list-unstyled text-light footer-link-list">
                    <li>
                        <i class="fas fa-map-marker-alt fa-fw"></i>
                        <?= Html::encode($empresa->rua . ' - ' . $empresa->localidade) ?>
                    </li>
                    <li>
                        <i class="fa fa-phone fa-fw"></i>
                        <a class="text-decoration-none" href="tel:<?= Html::encode($empresa->telefone ?? '') ?>">
                            <?= Html::encode($empresa->telefone ?? 'Telefone não disponível') ?>
                        </a>
                    </li>
                    <li>
                        <i class="fa fa-envelope fa-fw"></i>
                        <a class="text-decoration-none" href="mailto:<?= Html::encode($empresa->email ?? '') ?>">
                            <?= Html::encode($empresa->email ?? 'Email não disponível') ?>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Categorias de Produtos -->
            <div class="col-md-4 pt-5">
                <h2 class="h2 text-success border-bottom pb-3 border-light">Categorias</h2>
                <ul class="list-unstyled text-light footer-link-list">
                    <?php foreach ($categorias as $categoria): ?>
                        <li>
                            <a class="text-decoration-none" href="<?= Url::to(['produtos/index', 'categorias_id' => $categoria->id]) ?>">
                                <?= Html::encode($categoria->nome) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Links Adicionais -->
            <div class="col-md-4 pt-5">
                <h2 class="h2 text-success border-bottom pb-3 border-light">Navegação</h2>
                <ul class="list-unstyled text-light footer-link-list">
                    <li><a class="text-decoration-none" href="<?= Url::to(['site/index']) ?>">Início</a></li>
                    <li><a class="text-decoration-none" href="<?= Url::to(['site/about']) ?>">Sobre</a></li>
                    <li><a class="text-decoration-none" href="<?= Url::to(['produtos/index']) ?>">Produtos</a></li>
                    <li><a class="text-decoration-none" href="<?= Url::to(['site/contact']) ?>">Contactos</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Créditos -->
    <div class="w-100 bg-black py-3">
        <div class="container">
            <div class="row pt-2">
                <div class="col-12">
                    <p class="text-left text-light">
                        Copyright &copy; <?= date('Y') ?> <?= Html::encode($empresa->nome ?? 'Groove Tech') ?>
                        | Design por <a href="https://templatemo.com" target="_blank">TemplateMo</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>


<?php $this->endBody() ?>
<!-- Scripts -->
<script src="<?= Url::to('@web/js/custom.js') ?>"></script>
<script src="<?= Url::to('@web/js/jquery-1.11.0.min.js') ?>"></script>
<script src="<?= Url::to('@web/js/jquery-migrate-1.2.1.min.js') ?>"></script>
<script src="<?= Url::to('@web/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= Url::to('@web/js/templatemo.js') ?>"></script>

</body>
</html>
<?php $this->endPage() ?>
