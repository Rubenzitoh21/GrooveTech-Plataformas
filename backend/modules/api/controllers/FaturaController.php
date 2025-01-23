<?php

namespace backend\modules\api\controllers;

use Carbon\Carbon;
use common\models\Carrinhos;
use common\models\LinhasFaturas;
use common\models\ProdutosCarrinhos;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class FaturaController extends ActiveController
{
    public $modelClass = 'common\models\Faturas';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    public function actionCreateFatura()
    {

        $pagamentos_id = (int)Yii::$app->request->post('pagamentos_id');
        $this->checkRequiredParam($pagamentos_id, 'pagamentos_id');

        $expedicoes_id = (int)Yii::$app->request->post('expedicoes_id');
        $this->checkRequiredParam($expedicoes_id, 'expedicoes_id');

        $userId = Yii::$app->user->identity->id;

        $existingCart = Carrinhos::findOne([
            'user_id' => $userId,
            'status' => "Ativo",
        ]);

        $fatura = new $this->modelClass;
        $fatura->data = Carbon::now();
        $fatura->valortotal = $existingCart->valortotal;
        $fatura->status = "Pago";
        $fatura->user_id = $userId;
        $fatura->pagamentos_id = $pagamentos_id;
        $fatura->expedicoes_id = $expedicoes_id;
        $fatura->save();
        Yii::info($fatura->getErrors(), "debug");

        $linhasFaturas = new LinhasFaturas();
        $cartLines = ProdutosCarrinhos::find()->where(['carrinhos_id' => $existingCart->id])->all();

        foreach ($cartLines as $cartLine) {
            $linhasFaturas = new LinhasFaturas();
            $linhasFaturas->faturas_id = $fatura->id;
            $linhasFaturas->produtos_id = $cartLine->produtos_id;
            $linhasFaturas->quantidade = $cartLine->quantidade;
            $linhasFaturas->preco_venda = $cartLine->preco_venda;
            $linhasFaturas->valor_iva = $cartLine->valor_iva;
            $linhasFaturas->subtotal = $cartLine->subtotal;
            $linhasFaturas->save();


            $cartLine->delete();
        }
        $existingCart->delete();

        return [
            'message' => 'Fatura criada com sucesso e linhas de fatura criadas com sucesso.',
            'fatura' => $fatura,
            'linhasFaturas' => $linhasFaturas
        ];
    }

    //get fatura by user id
    public function actionGetFaturaByUserid($id)
    {
        $authenticatedUserId = Yii::$app->user->id;

        $fatura = $this->modelClass::find()->where(['user_id' => $id])->all();

        if (!$fatura) {
            $this->sendErrorResponse(404, 'Fatura não encontrada para o utilizador com o ID ' . $id);
        }

        return $fatura;
    }

    private function checkRequiredParam($param, $paramName)
    {
        if (!$param) {
            $this->sendErrorResponse(400, "Parâmetro obrigatório em falta: {$paramName}");
        }
    }
    private function sendErrorResponse($code, $message, $details = [])
    {
        Yii::$app->response->statusCode = $code;

        $responseData = [
            'error' => [
                'code' => $code,
                'message' => $message,
            ]
        ];

        if ($details) {
            $responseData['error']['details'] = $details;
        }

        Yii::$app->response->data = $responseData;
        Yii::$app->response->send();
        Yii::$app->end();
    }
}