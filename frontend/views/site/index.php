<?php

/** @var yii\web\View $this */
/** @var string $content */

use yii\helpers\Url;

$this->title = 'Groove Tech - Início';

?>
<!-- Start Banner Hero -->
<div id="template-mo-zay-hero-carousel" class="carousel slide" data-bs-ride="carousel">
    <ol class="carousel-indicators">
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="1"></li>
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                        <img class="img-fluid" src="<?= Url::to('@web/images/banner_01.png') ?>" alt="">
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left align-self-center">
                            <h1 class="h1 text-success"><b>Bem-vindo à Groove Tech</b></h1>
                            <h3 class="h2">Viva o Som, Sinta a Paixão!</h3>
                            <p>
                                Descubra tudo diretamente no site.
                                Oferecemos uma experiência de compra simples, rápida e segura.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                        <img class="img-fluid" src="<?= Url::to('@web/images/banner_02.png') ?>" alt="">
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left">
                            <h1 class="h1">Explore a Nossa Coleção</h1>
                            <h3 class="h2">Temos todo o tipo de instrumentos musicais à sua espera</h3>
                            <p>
                                Instrumentos de Qualidade: Trabalhamos com marcas reconhecidas mundialmente e produtos testados para oferecer a melhor experiência sonora.
                            </p>
                            <p>
                                Acessórios Essenciais: Capas, cordas, palhetas, suportes e muito mais para garantir que a sua performance seja impecável.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                        <img class="img-fluid" src="<?= Url::to('@web/images/banner_03.png') ?>" alt="">
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left">
                            <h1 class="h1">Faça Parte da Nossa Comunidade</h1>
                            <p>
                                Junte-se à comunidade GrooveTech e partilhe connosco a sua paixão pela música.
                                Queremos fazer parte da sua jornada musical.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev text-decoration-none w-auto ps-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="prev">
        <i class="fas fa-chevron-left"></i>
    </a>
    <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="next">
        <i class="fas fa-chevron-right"></i>
    </a>
</div>
<!-- End Banner Hero -->

<!-- Start Categories of The Month -->
<section class="container py-5">
    <div class="row text-center pt-3">
        <div class="col-lg-6 m-auto">
            <h1 class="h1">Categorias em Destaque</h1>
            <p>
                Descubra as nossas categorias mais procuradas e encontre tudo o que precisa para elevar a sua paixão pela música!
            </p>
        </div>
    </div>
    <div class="row">
        <?php foreach ($categoriasDestaque as $categoria): ?>
            <?php
            $produtos = $categoria->produtos;
            if (!empty($produtos)) {
                $produtoAleatorio = $produtos[array_rand($produtos)];
                $produtoImagem = $produtoAleatorio->imagens[0]->fileName ?? 'default.png';
            } else {
                $produtoImagem = 'default.png';
            }
            ?>
            <div class="col-12 col-md-4 p-5 mt-3 text-center">
                <a href="<?= Url::to(['produtos/index', 'categorias_id' => $categoria->id]) ?>">
                    <img src="<?= Url::to('@web/images/' . $produtoImagem) ?>" class="category-circle img-fluid">
                </a>
                <h5 class="text-center mt-3 mb-3"><?= $categoria->nome ?></h5>
                <p class="text-center"><?= $categoria->obs ?></p>
            </div>
        <?php endforeach; ?>
    </div>




</section>
<!-- End Categories of The Month -->

<section class="bg-light">
    <div class="container py-5">
        <!-- Produtos em Destaque -->
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Produtos em Destaque</h1>
                <p>Descubra os produtos mais bem avaliados e transforme a sua experiência musical.</p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($produtosDestaque as $produto): ?>
                <div class="col-12 col-md-4 mb-4">
                    <div class="card h-100 mb-4 product-wap rounded-0">
                        <div class="card rounded-0">
                            <img src="<?= Url::to('@web/images/' . ($produto['image_file'] ?? 'default.png')) ?>" class="card-img-top" alt="<?= $produto['nome'] ?>">
                            <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                <ul class="list-unstyled">
                                    <li><a class="btn btn-success text-white mt-2"
                                           href="<?= Url::to(['produtos/view', 'id' => $produto['id']]) ?>"><i
                                                    class="far fa-eye"></i></a></li>
                                    <li><a class="btn btn-success text-white mt-2"
                                           href="<?= Url::to(['produtos-carrinhos/create', 'produtos_id' => $produto['id']]) ?>"><i
                                                    class="fas fa-cart-plus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled d-flex justify-content-between">
                                <li>
                                    <?php
                                    $rating = round($produto['avg_rating']);
                                    for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= $i <= $rating ? 'text-warning' : 'text-muted' ?> fa fa-star"></i>
                                    <?php endfor; ?>
                                </li>
                                <li class="text-muted text-right"><?= number_format($produto['preco'],2, ',', '.') ?> €</li>
                            </ul>
                            <a href="<?= Url::to(['produtos/view', 'id' => $produto['id']]) ?>" class="h2 text-decoration-none text-dark">
                                <?= $produto['nome'] ?>
                            </a>
                            <p class="card-text">
                                <?= $produto['descricao'] ?>
                            </p>
                            <p class="text-muted">Comentários <sup>(<?= $produto['review_count'] ?>)</sup></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

