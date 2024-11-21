<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class PagamentoController extends ActiveController
{
    public $modelClass = 'common\models\Pagamentos';

    public function actionIndex()
    {
        return $this->render('index');
    }
}
{

}