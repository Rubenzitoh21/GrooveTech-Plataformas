<?php

namespace frontend\controllers;

use common\models\Avaliacoes;
use common\models\LinhasFaturas;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * AvaliacoesController implements the CRUD actions for Avaliacoes model.
 */
class AvaliacoesController extends Controller
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
                    'only' => ['index', 'create', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'delete'],
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


    /**
     * Lists all Avaliacoes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('verAvaliacoes')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $userId = Yii::$app->user->id;

        $linhasFatura = LinhasFaturas::find()
            ->select([
                'produtos_id',
                'MAX(linhas_faturas.id) as id',
                'SUM(quantidade) as quantidade',
                'SUM(subtotal) as subtotal',
            ])
            ->joinWith('faturas')
            ->where(['faturas.user_id' => $userId])
            ->groupBy('produtos_id')
            ->all();

        return $this->render('index', [
            'linhasFatura' => $linhasFatura,
        ]);
    }

//    /**
//     * Displays a single Avaliacoes model.
//     * @param int $id ID
//     * @return string
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionView($id)
//    {
//        if (!Yii::$app->user->can('verAvaliacoes')) {
//            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
//        }
//
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

    /**
     * Creates a new Avaliacoes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($linhaFaturaId)
    {
        if (!Yii::$app->user->can('criarAvaliacoes')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = new Avaliacoes();
        $model->linhas_faturas_id = $linhaFaturaId;
        $model->user_id = Yii::$app->user->id;
        $model->dtarating = date('Y-m-d H:i:s');

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['produtos/view', 'id' => $model->linhasFaturas->produtos_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'linhaFaturaId' => $linhaFaturaId,
        ]);
    }



//
//    /**
//     * Updates an existing Avaliacoes model.
//     * If update is successful, the browser will be redirected to the 'view' page.
//     * @param int $id ID
//     * @return string|\yii\web\Response
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Deletes an existing Avaliacoes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('apagarAvaliacoes')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = $this->findModel($id);

        if ($model && $model->user_id == Yii::$app->user->id) {
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * Finds the Avaliacoes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Avaliacoes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Avaliacoes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
