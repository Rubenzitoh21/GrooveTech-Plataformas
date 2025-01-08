<?php

namespace backend\controllers;

use common\models\CategoriasProdutos;
use common\models\CategoriasProdutosSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriasProdutosController implements the CRUD actions for CategoriasProdutos model.
 */
class CategoriasProdutosController extends Controller
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
     * Lists all CategoriasProdutos models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('verCategorias')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $searchModel = new CategoriasProdutosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CategoriasProdutos model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('verCategorias')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CategoriasProdutos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($urlCallback = null)
    {
        if (!Yii::$app->user->can('criarCategorias')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = new CategoriasProdutos();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                if($urlCallback === 'produto'){
                    return $this->redirect(['produtos/create']);
                } else {
                    return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing CategoriasProdutos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('editarCategorias')) {
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
     * Deletes an existing CategoriasProdutos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('apagarCategorias')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = $this->findModel($id);
        try {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Categoria eliminada com sucesso.');
        } catch (\yii\db\IntegrityException $e) {
            Yii::$app->session->setFlash('error', 'Não é possível eliminar esta Categoria porque está associado a outros registos.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Ocorreu um erro ao tentar eliminar a Categoria.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the CategoriasProdutos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CategoriasProdutos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CategoriasProdutos::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página solicitada não existe.');
    }
}
