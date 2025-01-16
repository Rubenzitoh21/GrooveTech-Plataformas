<?php

namespace backend\modules\api\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class LinhasFaturaController extends ActiveController

{
    public $modelClass = 'common\models\LinhasFaturas';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    //get all linhas fatura by fatura id
    public function actionGetAllLinhasFaturaByFaturaId($fatura_id)
    {
        $linhasFatura = $this->modelClass::find()
            ->where(['faturas_id' => $fatura_id])
            ->all();

        if (empty($linhasFatura)) {
            throw new NotFoundHttpException("Não existem linhas de fatura");
        }

        return $linhasFatura;
    }

}