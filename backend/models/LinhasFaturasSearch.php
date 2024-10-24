<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LinhasFaturas;

/**
 * LinhasFaturasSearch represents the model behind the search form of `common\models\LinhasFaturas`.
 */
class LinhasFaturasSearch extends LinhasFaturas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'faturas_id', 'avaliacoes_id'], 'integer'],
            [['quantidade'], 'safe'],
            [['preco_venda', 'valor_iva', 'subtotal'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = LinhasFaturas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'preco_venda' => $this->preco_venda,
            'valor_iva' => $this->valor_iva,
            'subtotal' => $this->subtotal,
            'faturas_id' => $this->faturas_id,
            'avaliacoes_id' => $this->avaliacoes_id,
        ]);

        $query->andFilterWhere(['like', 'quantidade', $this->quantidade]);

        return $dataProvider;
    }
}
