<?php

namespace frontend\controllers;

use common\models\CategoriasProdutos;
use common\models\LinhasFaturas;
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
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['index', 'view'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view'],
                            'allow' => true,
                            'roles' => ['?', '@'],
                        ],
                    ],
                    'denyCallback' => function ($rule, $action) {
                        throw new \yii\web\ForbiddenHttpException('Acesso negado.');
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
        $model = Produtos::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Produto não encontrado.');
        }

        $foiComprado = $this->foiComprado($id);
        $ultimaLinhaFaturaId = $this->getUltimaLinhaFatura($id);

        return $this->render('view', [
            'model' => $model,
            'foiComprado' => $foiComprado,
            'ultimaLinhaFaturaId' => $ultimaLinhaFaturaId,
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
    public function foiComprado($produtoId)
    {
        $userId = Yii::$app->user->id;

        return LinhasFaturas::find()
            ->joinWith('faturas')
            ->where([
                'faturas.user_id' => $userId,
                'linhas_faturas.produtos_id' => $produtoId,
            ])
            ->exists();
    }

    public function getUltimaLinhaFatura($produtoId)
    {
        $userId = Yii::$app->user->id;

        $linhaFatura = LinhasFaturas::find()
            ->joinWith('faturas')
            ->where([
                'faturas.user_id' => $userId,
                'linhas_faturas.produtos_id' => $produtoId,
            ])
            ->orderBy(['linhas_faturas.id' => SORT_DESC]) // Ordena pela última criada
            ->one();

        return $linhaFatura ? $linhaFatura->id : null;
    }
}
