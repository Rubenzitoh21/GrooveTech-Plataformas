<?php

namespace backend\modules\api\controllers;

use common\models\Faturas;
use common\models\LinhasFaturas;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

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
    public function actionGetAllLinhasFaturaByFaturaId($id)
    {
        $fatura = Faturas::findOne($id);
        $linhasFatura = $this->modelClass::find()
            ->where(['faturas_id' => $id])
            ->all();

        $linhasFaturasData = LinhasFaturas::find()
            ->where(['faturas_id' => $id])
            ->all();

        $totalIva = 0;
        $subtotal = 0;
        foreach ($linhasFaturasData as $linha) {
            $totalIva += $linha->valor_iva;

        }
        $subtotal = $fatura->valortotal - $totalIva;

        if (empty($linhasFatura)) {
            throw new NotFoundHttpException("Não existem linhas de fatura");
        }

        $response = [];

        foreach ($linhasFatura as $linha) {
            $response[] = [
                'id' => $linha->id,
                'quantidade' => $linha->quantidade,
                'preco_venda' => $linha->preco_venda,
                'valor_iva' => $linha->valor_iva,
                'subtotal' => $linha->subtotal,
                'faturas_id' => $linha->faturas_id,
                'produtos_id' => $linha->produtos_id
            ];
        }

        $response[] = [
            'totals' => [
                'totalIva' => $totalIva,
                'subTotalLinhas' => $subtotal
            ]
        ];

        return $response;
    }

}