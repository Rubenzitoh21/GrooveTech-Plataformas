<?php

namespace backend\controllers;

use common\models\Produtos;
use common\models\ProdutosSearch;
use common\models\Imagens;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProdutosController implements the CRUD actions for Produtos model.
 */
class ProdutosController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'view', 'delete', 'create', 'update'],
                            'roles' => ['gestor'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Produtos models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('verProdutos')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $searchModel = new ProdutosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Produtos model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('verProdutos')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Produtos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('criarProdutos')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = new Produtos();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Produto criado com sucesso.');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Ocorreu um erro ao criar o Produto.');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Produtos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('editarProdutos')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Produto atualizado com sucesso.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Ocorreu um erro ao atualizar o Produto.');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Produtos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('apagarProdutos')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = $this->findModel($id);

        try {
            if ($model->delete()) {
                $imagens = Imagens::find()->where(['produto_id' => $model->id])->all();

                foreach ($imagens as $imagem) {
                    $imagem->delete();
                    unlink(Yii::getAlias('@frontend/web/images/') . $imagem->fileName);
                    unlink(Yii::getAlias('@backend/web/images/') . $imagem->fileName);
                }

                Yii::$app->session->setFlash('success', 'Produto eliminado com sucesso. (Incluindo as imagens associadas)');
            }
        } catch (\yii\db\IntegrityException $e) {
            Yii::$app->session->setFlash('error', 'Não é possível eliminar este Produto porque está associado a outros registos.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Ocorreu um erro ao tentar eliminar o Produto.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Produtos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Produtos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Produtos::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página solicitada não existe.');
    }
}
