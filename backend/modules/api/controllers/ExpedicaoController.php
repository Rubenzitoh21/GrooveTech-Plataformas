<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class ExpedicaoController extends ActiveController
{
    public $modelClass = 'common\models\Expedicoes';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetAllExpedicoes()
    {
        $expedicoes = $this->modelClass::find()
            ->all();

        if (empty($expedicoes)) {
            throw new NotFoundHttpException("Não existem expedições");
        }

        return $expedicoes;
    }
}