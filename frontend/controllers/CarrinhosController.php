<?php

namespace frontend\controllers;

use Carbon\Carbon;
use common\models\Carrinhos;
use common\models\CarrinhosSearch;
use common\models\Faturas;
use common\models\LinhasFaturas;
use common\models\Pagamentos;
use common\models\ProdutosCarrinhos;
use common\models\UserProfile;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CarrinhosController implements the CRUD actions for Carrinhos model.
 */
class CarrinhosController extends Controller
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
                    'class' => AccessControl::class,
                    'only' => ['index', 'create', 'update', 'delete', 'view', 'checkout', 'aumentar', 'diminuir'],
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'update', 'delete', 'view', 'checkout', 'aumentar', 'diminuir'],
                            'allow' => true,
                            'roles' => ['cliente'],
                        ],


                    ],
                    'denyCallback' => function ($rule, $action) {
                        if (Yii::$app->user->isGuest) {
                            // Redirect unauthenticated users to the login page
                            Yii::$app->getResponse()->redirect(['site/login'])->send();
                            Yii::$app->end();
                        } else {
                            throw new ForbiddenHttpException('Acesso negado.');
                        }
                    },
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'logout' => ['post'],
                    ],

                ],
            ]
        );
    }

    /**
     * Lists all Carrinhos models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('verCompras')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado');
        }

        $searchModel = new CarrinhosSearch();
        $searchModel->user_id = Yii::$app->user->id;
        $dataProvider = $searchModel->search($this->request->queryParams);
        if ($dataProvider->getModels() == null) {
            $this->actionCreate();

            $searchModel = new CarrinhosSearch();
            $searchModel->user_id = Yii::$app->user->id;
            $dataProvider = $searchModel->search($this->request->queryParams);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Carrinhos model.
     * @param int $id ID
     * @param int $user_id User ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $user_id)
    {
        if (!Yii::$app->user->can('verCompras')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado');
        }

        return $this->render('view', [
            'model' => $this->findModel($id, $user_id),
        ]);
    }

    /**
     * Creates a new Carrinhos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('fazerCompras')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado');
        }

        $model = new Carrinhos();

        $model->user_id = Yii::$app->user->id;
        $model->status = 'Ativo';
        $model->valortotal = 0;
        $model->dtapedido = carbon::now();
        $model->save();

        return $this->actionIndex();
    }

    /**
     * Updates an existing Carrinhos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @param int $user_id User ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */


    public function actionUpdate($id, $user_id)
    {
        if (!Yii::$app->user->can('editarCompras')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado');
        }

        $model = $this->findModel($id, $user_id);
        $userData = UserProfile::findOne(['user_id' => Yii::$app->user->id]);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            var_dump($model->errors);
            return $this->redirect(['carrinhos/index', 'id' => $model->id, 'user_id' => $model->user_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'userData' => $userData,
        ]);
    }

    public function actionCheckout($id, $user_id)
    {
        if (!Yii::$app->user->can('fazerCompras')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado');
        }

        $model = $this->findModel($id, $user_id);
        $userData = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        $userData?->setScenario(UserProfile::SCENARIO_USERPROFILE);
        $fatura = new Faturas();
        $produtoCarrinhoProduto = ProdutosCarrinhos::find()->where(['carrinhos_id' => $model->id])->all();

        if ($this->request->isPost) {

            $fatura->load(Yii::$app->request->post());
            $fatura->data = Carbon::now();
            $fatura->valortotal = $model->valortotal;
            $fatura->status = 'Pago';
            $fatura->user_id = Yii::$app->user->id;

            if (empty($fatura->pagamentos_id) || empty($fatura->expedicoes_id)) {
                Yii::$app->session->setFlash('error', 'Por favor, selecione um método de pagamento e um método de envio.');
                return $this->render('checkout', [
                    'model' => $model,
                    'userData' => $userData,
                    'fatura' => $fatura,
                ]);
            }

            if (!$userData->validate() || !$fatura->validate()) {
                $errorMessages = '';
                foreach ($userData->errors as $attributeErrors) {
                    foreach ($attributeErrors as $errorMessage) {
                        $errorMessages .= $errorMessage . '<br>';
                    }
                }
                foreach ($fatura->errors as $attributeErrors) {
                    foreach ($attributeErrors as $errorMessage) {
                        $errorMessages .= $errorMessage . '<br>';
                    }
                }

                Yii::$app->session->setFlash('error', 'Detalhes de Faturação inválidos');

                return $this->render('checkout', [
                    'model' => $model,
                    'userData' => $userData,
                    'fatura' => $fatura,
                ]);
            }
            $fatura->save();

            foreach ($produtoCarrinhoProduto as $produtoCarrinho) {
                $linhaFatura = new LinhasFaturas();
                $linhaFatura->quantidade = $produtoCarrinho->quantidade;
                $linhaFatura->preco_venda = $produtoCarrinho->preco_venda;
                $linhaFatura->valor_iva = $produtoCarrinho->valor_iva;
                $linhaFatura->subtotal = $produtoCarrinho->subtotal;
                $linhaFatura->faturas_id = $fatura->id;
                $linhaFatura->produtos_id = $produtoCarrinho->produtos_id;
                $linhaFatura->save();
            }

            // Apagar as linhas do carrinho
            foreach ($produtoCarrinhoProduto as $produtoCarrinho) {
                $produtoCarrinho->delete();
            }

            // Apagar o carrinho
            $model->delete();

            return $this->redirect(['faturas/view', 'id' => $fatura->id]);
        }

        return $this->render('checkout', [
            'model' => $model,
            'userData' => $userData,
            'fatura' => $fatura,
        ]);
    }


    public function actionAumentar($id)
    {
        if (!Yii::$app->user->can('editarCompras')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado');
        }

        $linha = ProdutosCarrinhos::findOne($id);
        $carrinho = Carrinhos::findOne($linha->carrinhos_id);

        if ($linha) {
            $linha->quantidade = (intval($linha->quantidade) + 1) . '';
            $linha->subtotal = $linha->quantidade * $linha->preco_venda;
            $carrinho->valortotal = $carrinho->valortotal + $linha->preco_venda;

            $linha->save();
            $carrinho->save();
        }

        return $this->redirect(['index']);
    }

    public function actionDiminuir($id)
    {
        if (!Yii::$app->user->can('editarCompras')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado');
        }

        $linha = ProdutosCarrinhos::findOne($id);
        $carrinho = Carrinhos::findOne($linha->carrinhos_id);
        if ($linha && $linha->quantidade != '1') {
            $linha->quantidade = (intval($linha->quantidade) - 1) . '';
            $linha->subtotal = $linha->quantidade * $linha->preco_venda;
            $carrinho->valortotal = $carrinho->valortotal - $linha->preco_venda;
            $linha->save();
            $carrinho->save();

        }

        return $this->redirect(['index']);
    }

    public function actionUserdataupdate($id, $user_id)
    {
        if (!Yii::$app->user->can('editarDadosPessoais')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado');
        }

        $userDataAdditional = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        $model = $this->findModel($id, $user_id);
        if ($this->request->isPost) {
            // Load data from the post request

            $userDataAdditional->load(Yii::$app->request->post());

            // Validate and save the user data models

            if ($userDataAdditional->save()) {
                Yii::$app->session->setFlash('success', 'Dados atualizados com sucesso!');

                return $this->redirect(['checkout',
                        'id' => $model->id,
                        'user_id' => $model->user_id,]
                );
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar os dados.');
            }


        }

        // Redirect back to the current page
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    /**
     * Deletes an existing Carrinhos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @param int $user_id User ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $user_id)
    {
        if (!Yii::$app->user->can('editarCompras')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado');
        }

        $this->findModel($id, $user_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Carrinhos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param int $user_id User ID
     * @return Carrinhos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $user_id)
    {
        if (($model = Carrinhos::findOne(['id' => $id, 'user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
