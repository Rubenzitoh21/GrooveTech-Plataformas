<?php

namespace frontend\controllers;

use Carbon\Carbon;
use common\models\Carrinhos;
use common\models\Produtos;
use common\models\ProdutosCarrinhos;
use common\models\ProdutosCarrinhosSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProdutosCarrinhosController implements the CRUD actions for ProdutosCarrinhos model.
 */
class ProdutosCarrinhosController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['create', 'update', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['create', 'update', 'delete'],
                            'allow' => true,
                            'roles' => ['cliente'],
                        ],
                    ],
                    'denyCallback' => function ($rule, $action) {
                        if (Yii::$app->user->isGuest) {
                            Yii::$app->getResponse()->redirect(['site/login'])->send();
                            Yii::$app->end();
                        } else {
                            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
                        }
                    },
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }


//    /**
//     * Lists all ProdutosCarrinhos models.
//     *
//     * @return string
//     */
//    public function actionIndex()
//    {
//        if (!Yii::$app->user->can('verCompras')) {
//            throw new \yii\web\ForbiddenHttpException('Acesso negado');
//        }
//
//        $searchModel = new ProdutosCarrinhosSearch();
//        $dataProvider = $searchModel->search($this->request->queryParams);
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
//    }
//
//    /**
//     * Displays a single ProdutosCarrinhos model.
//     * @param int $id ID
//     * @param int $carrinhos_id Carrinhos ID
//     * @param int $produtos_id Produtoss ID
//     * @return string
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionView($id, $carrinhos_id, $produtos_id)
//    {
//        if (!Yii::$app->user->can('verCompras')) {
//            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
//        }
//
//        return $this->render('view', [
//            'model' => $this->findModel($id, $carrinhos_id, $produtos_id),
//        ]);
//    }

    /**
     * Creates a new ProdutosCarrinhos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($produtos_id)
    {
        if (!Yii::$app->user->can('fazerCompras')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = new ProdutosCarrinhos();
        $modelProdutos = Produtos::findOne(['id' => $produtos_id]);
        $modelCarrinhos = Carrinhos::find()->where(['user_id' => Yii::$app->user->id, 'status' => 'Ativo'])->one();
        if($modelCarrinhos == null){
            $modelCarrinhos = new Carrinhos();
            $modelCarrinhos->user_id = Yii::$app->user->id;
            $modelCarrinhos->status = 'Ativo';
            $modelCarrinhos->valortotal = 0;
            $modelCarrinhos->dtapedido = Carbon::now();
            $modelCarrinhos->save();

        }
        $model->carrinhos_id = $modelCarrinhos->id;
        $linhaCarrinho = ProdutosCarrinhos::find()->where(['carrinhos_id' => $modelCarrinhos->id, 'produtos_id' => $produtos_id])->one();
        // Set other attributes and validation as needed
        $model->produtos_id = $produtos_id;

        if($linhaCarrinho != null){
            $linhaCarrinho->quantidade = (intval($linhaCarrinho->quantidade) + 1) . '';
            $linhaCarrinho->subtotal += $modelProdutos->preco;
            $linhaCarrinho->save();
            $modelCarrinhos->valortotal = $modelCarrinhos->valortotal + $modelProdutos->preco;
            $modelCarrinhos->save();
            $linhaCarrinho->save();

            return $this->redirect(['carrinhos/index']);
        }

        $model->quantidade = "1";
        $model->valor_iva = $modelProdutos->preco * ($modelProdutos->ivas->percentagem / 100) * $model->quantidade;
        $model->subtotal = $modelProdutos->preco * $model->quantidade;
        $model->preco_venda = $modelProdutos->preco;

        $modelCarrinhos->valortotal = $modelCarrinhos->valortotal + $model->subtotal;

        if ($model->save() && $modelCarrinhos->save()) {
            return $this->redirect(['carrinhos/index']);
        } else {

            Yii::$app->session->setFlash('error', 'Para realizar compras, tem que iniciar sessão!');
        }

        return $this->redirect(['carrinhos/index']);
    }



    /**
     * Updates an existing ProdutosCarrinhos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('editarCompras')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProdutosCarrinhos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('editarCompras')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }
        $model = $this->findModel($id);
        $modelCarrinhos = Carrinhos::find()->where(['id' => $model->carrinhos_id])->one();
        $modelCarrinhos->valortotal = $modelCarrinhos->valortotal - $model->subtotal;
        $modelCarrinhos->save();

        $model->delete();

        return $this->redirect(['carrinhos/index']);
    }

    /**
     * Finds the ProdutosCarrinhos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ProdutosCarrinhos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProdutosCarrinhos::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
