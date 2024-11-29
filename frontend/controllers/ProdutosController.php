<?php

namespace frontend\controllers;

use common\models\CategoriasProdutos;
use common\models\Produtos;
use common\models\ProdutosSearch;
use Yii;
use yii\data\ActiveDataProvider;
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
            ]
        );
    }

    public function actionIndex($categorias_id = null, $search = null)
    {
        $query = Produtos::find();


        if ($categorias_id !== null) {
            $query->andWhere(['categorias_produtos_id' => $categorias_id]);
        }

        if ($search !== null) {
            $query->andWhere(['like', 'nome', $search]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        $categorias = CategoriasProdutos::find()->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'categorias' => $categorias,
            'categorias_id' => $categorias_id,
            'search' => $search,
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
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

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
