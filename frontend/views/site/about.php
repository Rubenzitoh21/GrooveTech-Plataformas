<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Sobre';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <?= Html::img('@web/images/logo_gt.png', [
                'alt' => 'Groove Tech Logo',
                'class' => 'img-thumbnail'
            ]) ?>
        </div>
    </div>
</div>
