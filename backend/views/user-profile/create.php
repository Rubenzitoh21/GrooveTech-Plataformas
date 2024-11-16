<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\UserProfile $model */

$this->title = 'Create User Profile';
$this->params['breadcrumbs'][] = ['label' => 'User Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
