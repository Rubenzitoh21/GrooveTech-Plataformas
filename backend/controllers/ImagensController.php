<?php

namespace backend\controllers;

use common\models\Imagens;
use backend\models\ImagensSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ImagensController implements the CRUD actions for Imagens model.
 */
class ImagensController extends Controller
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
                            'actions' => ['index', 'view', 'update', 'create', 'delete'],
                            'roles' => ['gestor'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Imagens models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('editarProdutos')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $searchModel = new ImagensSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Imagens model.
     * @param int $id ID
     * @param int $produto_id Produto ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('editarProdutos')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Imagens model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($produto_id = null, $urlCallback = null)
    {
        if (!Yii::$app->user->can('editarProdutos')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = new Imagens();

        if ($produto_id != null) {
            $model->produto_id = $produto_id;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

                if ($uploadPaths = $model->upload()) {
                    foreach ($uploadPaths as $file) {
                        $newModel = new Imagens();
                        $newModel->imageFiles = UploadedFile::getInstances($newModel, 'imageFiles');

                        $fileImagem = pathinfo($file);

                        $newModel->fileName = $fileImagem['basename'];
                        $newModel->produto_id = $model->produto_id;
                        // If $produto_id is provided, set it in the new model
                        if ($produto_id != null) {
                            $newModel->produto_id = $produto_id;
                        }

                        $newModel->save();
                    }

                    if($urlCallback === 'produto'){
                        return $this->redirect(['produtos/view', 'id' => $model->produto_id]);
                    } else {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
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
     * Updates an existing Imagens model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @param int $produto_id Produtos ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $urlCallback = null)
    {
        if (!Yii::$app->user->can('editarProdutos')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            if ($uploadPaths = $model->upload()) {
                foreach ($uploadPaths as $file) {
                    $fileInfo = pathinfo($file);
                    $model->fileName = $fileInfo['basename'];

                    if (!$model->save()) {
                        Yii::$app->session->setFlash('error', 'Erro ao guardar as alterações.');
                        return $this->redirect(['update', 'id' => $model->id]);
                    }
                }

                if($urlCallback === 'produto'){
                    return $this->redirect(['produtos/view', 'id' => $model->produto_id]);
                } else {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Imagens model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @param int $produto_id Produtos ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('editarProdutos')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Imagens model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param int $produto_id Produtos ID
     * @return Imagens the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Imagens::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página solicitada não existe.');
    }
}
