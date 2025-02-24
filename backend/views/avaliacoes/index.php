<?php

use common\models\Avaliacoes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\AvaliacoesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Avaliações';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="avaliacoes-index">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'produto_id',
                'label' => 'Produto',
                'format' => 'raw',
                'value' => function ($model) {
                    $produto = $model->linhasFaturas->produtos ?? null;
                    if ($produto) {
                        return Html::a(
                            Html::encode($produto->nome),
                            ['produtos/view', 'id' => $produto->id]
                        );
                    }
                    return 'Produto não disponível';
                },
            ],
            'comentario',
            'dtarating',
            [
                'attribute' => 'rating',
                'format' => 'raw',
                'value' => function ($model) {
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $stars .= $i <= $model->rating
                            ? '<i class="fa fa-star text-warning"></i>' // Estrelas preenchidas
                            : '<i class="fa fa-star text-secondary"></i>'; // Estrelas vazias
                    }
                    return $stars;
                },
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'user_id',
                'label' => 'Utilizador',
                'format' => 'raw',
                'value' => function ($model) {
                    $user = $model->user;
                    if ($user) {
                        return Html::a(
                            Html::encode($user->userProfile->primeironome . ' ' . $user->userProfile->apelido),
                            ['user-profile/view', 'id' => $user->userProfile->id, 'user_id' => $user->id],
                        );
                    }
                    return 'Utilizador não disponível';
                },
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
                'urlCreator' => function ($action, Avaliacoes $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
