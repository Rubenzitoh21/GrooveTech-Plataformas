<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Sobre';
?>
<div class="site-about">
    <!-- Start Section -->
    <section class="container py-5">
        <div class="row text-center pt-5 pb-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Sobre Nós</h1>
                <p>
                    Na GrooveTech, vivemos e respiramos música. Com anos de experiência, somos apaixonados por oferecer
                    instrumentos musicais de qualidade e acessórios que inspiram músicos de todos os níveis. Seja para
                    iniciantes ou profissionais, estamos aqui para ajudá-lo a criar o som perfeito.
                </p>
            </div>
        </div>
        <div class="row text-center pt-5 pb-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">A Nossa Missão</h1>
                <p>
                    A nossa missão é ajudar músicos a encontrar o seu som. Quer esteja a começar ou a aperfeiçoar o
                    seu talento, estamos aqui para apoiá-lo em cada passo do caminho. Junte-se a nós e faça parte da
                    comunidade GrooveTech!
                </p>
            </div>
        </div>
        <div class="row text-center pt-5 pb-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Os Nossos Serviços</h1>
                <p>
                    Além de uma ampla seleção de instrumentos e acessórios, oferecemos serviços como manutenção de
                    instrumentos, aulas de música e consultoria para escolher o equipamento ideal. Tudo para garantir
                    que a música faça parte da sua vida!
                </p>
            </div>
        </div>
        <br>

        <div class="row">

            <div class="col-md-6 col-lg-3 pb-5">
                <div class="h-100 py-5 services-icon-wap shadow">
                    <div class="h1 text-success text-center"><i class="fa fa-truck fa-lg"></i></div>
                    <h2 class="h5 mt-4 text-center">Serviços de Entrega</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 pb-5">
                <div class="h-100 py-5 services-icon-wap shadow">
                    <div class="h1 text-success text-center"><i class="fas fa-exchange-alt"></i></div>
                    <h2 class="h5 mt-4 text-center">Envio e Devoluções</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 pb-5">
                <div class="h-100 py-5 services-icon-wap shadow">
                    <div class="h1 text-success text-center"><i class="fa fa-percent"></i></div>
                    <h2 class="h5 mt-4 text-center">Promoções</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 pb-5">
                <div class="h-100 py-5 services-icon-wap shadow">
                    <div class="h1 text-success text-center"><i class="fa fa-user"></i></div>
                    <h2 class="h5 mt-4 text-center">Serviço 24H</h2>
                </div>
            </div>
        </div>
    </section>
    <!-- End Section -->

    <!-- Start Brands -->
    <section class="bg-light py-5">
        <div class="container my-4">
            <div class="row text-center py-3">
                <div class="col-lg-10 m-auto">
                    <h1 class="h1">As Nossas Marcas Parceiras</h1>
                    <p>
                        Trabalhamos com as melhores marcas para garantir qualidade e excelência em cada produto.
                    </p>
                </div>
                <div class="col-lg-9 m-auto tempaltemo-carousel">
                    <div class="row d-flex flex-row">
                        <!--Controls-->
                        <div class="col-1 align-self-center">
                            <a class="h1" href="#templatemo-slide-brand" role="button" data-bs-slide="prev">
                                <i class="text-light fas fa-chevron-left"></i>
                            </a>
                        </div>
                        <!--End Controls-->

                        <!--Carousel Wrapper-->
                        <div class="col">
                            <div class="carousel slide carousel-multi-item pt-2 pt-md-0" id="templatemo-slide-brand" data-bs-ride="carousel">
                                <!--Slides-->
                                <div class="carousel-inner product-links-wap" role="listbox">

                                    <!--First slide-->
                                    <div class="carousel-item active">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <img class="img-fluid brand-img" src="<?= Url::to('@web/images/brand_01.png') ?>" alt="Brand Logo">
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <img class="img-fluid brand-img" src="<?= Url::to('@web/images/brand_02.png') ?>" alt="Brand Logo">
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <img class="img-fluid brand-img" src="<?= Url::to('@web/images/brand_03.png') ?>" alt="Brand Logo">
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <img class="img-fluid brand-img" src="<?= Url::to('@web/images/brand_04.png') ?>" alt="Brand Logo">
                                            </div>
                                        </div>
                                    </div>
                                    <!--End First slide-->

                                    <!--Second slide-->
                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <img class="img-fluid brand-img" src="<?= Url::to('@web/images/brand_05.png') ?>" alt="Brand Logo">
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <img class="img-fluid brand-img" src="<?= Url::to('@web/images/brand_06.png') ?>" alt="Brand Logo">
                                            </div>
                                            <div class="col-3 p-md-5">
                                               <img class="img-fluid brand-img" src="<?= Url::to('@web/images/brand_07.png') ?>" alt="Brand Logo">
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <img class="img-fluid brand-img" src="<?= Url::to('@web/images/brand_08.png') ?>" alt="Brand Logo">
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Second slide-->

                                </div>
                                <!--End Slides-->
                            </div>
                        </div>
                        <!--End Carousel Wrapper-->

                        <!--Controls-->
                        <div class="col-1 align-self-center">
                            <a class="h1" href="#templatemo-slide-brand" role="button" data-bs-slide="next">
                                <i class="text-light fas fa-chevron-right"></i>
                            </a>
                        </div>
                        <!--End Controls-->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Brands-->
</div>
