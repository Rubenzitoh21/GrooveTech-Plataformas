<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

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
    <link rel="icon" type="image/png" href="<?= Yii::getAlias('@web') ?>/images/logo_gt.png">
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

                <a class="nav-icon position-relative text-decoration-none" href="<?= Url::to(['/carrinhos/index']) ?>">
                    <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                    <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">0</span>
                </a>
                <a class="nav-icon position-relative text-decoration-none" href="<?= Yii::$app->user->isGuest ? Url::to(['/site/login']) : Url::to(['/site/perfil']) ?>">
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
<!-- Close Header -->

<main role="main" >
    <div class="container">
        <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p>&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
<script src="<?= Url::to('@web/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
<?php $this->endPage() ?>
