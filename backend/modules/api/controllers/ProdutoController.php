<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class ProdutoController extends ActiveController
{
    public $modelClass = 'common\models\Produtos';

    public function actionIndex()
    {
        return $this->render('index');
    }
}
