<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class ProdutoscarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\ProdutosCarrinhos';

    public function actionIndex()
    {
        return $this->render('index');
    }
}
