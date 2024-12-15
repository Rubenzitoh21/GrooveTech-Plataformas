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


    public function actionIndex($categorias_id = null, $search = null, $sort = null)
    {
        // Cria a consulta base
        $query = Produtos::find();

        // Filtrar por categoria (somente se existir uma categoria válida)
        if (!empty($categorias_id)) {
            $query->andWhere(['categorias_produtos_id' => $categorias_id]);
        }

        // Filtrar por pesquisa (somente se o valor de pesquisa for preenchido)
        if (!empty($search)) {
            $query->andWhere(['like', 'nome', $search]);
        }

        // Aplicar ordenação com base na seleção do dropdown
        switch ($sort) {
            case 'price_asc':
                $query->orderBy(['preco' => SORT_ASC]);
                break;
            case 'price_desc':
                $query->orderBy(['preco' => SORT_DESC]);
                break;
            case 'name_asc':
                $query->orderBy(['nome' => SORT_ASC]);
                break;
            case 'name_desc':
                $query->orderBy(['nome' => SORT_DESC]);
                break;
            case 'date_oldest':
                $query->orderBy(['id' => SORT_ASC]);
                break;
            case 'date_newest':
                $query->orderBy(['id' => SORT_DESC]);
                break;
            default:
                // Ordenação padrão
                $query->orderBy(['id' => SORT_DESC]);
        }

        // Configurar o DataProvider para paginação e consulta
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12, // Número de produtos por página
            ],
        ]);

        // Buscar todas as categorias
        $categorias = CategoriasProdutos::find()->all();

        // Renderizar a view com os dados necessários
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'categorias' => $categorias,
            'categorias_id' => $categorias_id,
            'search' => $search,
            'sort' => $sort,
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
