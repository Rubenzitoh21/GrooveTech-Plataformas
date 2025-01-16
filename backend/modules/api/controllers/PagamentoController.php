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

    public function actionGetAllPagamentos()
    {
        $pagamentos = $this->modelClass::find()
            ->all();

        if (empty($pagamentos)) {
            throw new NotFoundHttpException("Não existem pagamentos");
        }

        return $pagamentos;
    }

}