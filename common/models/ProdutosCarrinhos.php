<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "produtos_carrinhos".
 *
 * @property int $id
 * @property string $quantidade
 * @property float $preco_venda
 * @property float $valor_iva
 * @property float $subtotal
 * @property int $produtos_id
 * @property int $carrinhos_id
 *
 * @property Carrinhos $carrinhos
 * @property Produtos $produtos
 */
class ProdutosCarrinhos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produtos_carrinhos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantidade', 'preco_venda', 'valor_iva', 'subtotal', 'produtos_id', 'carrinhos_id'], 'required'],
            [['preco_venda', 'valor_iva', 'subtotal'], 'number'],
            [['produtos_id', 'carrinhos_id'], 'integer'],
            [['quantidade'], 'string', 'max' => 45],
            [['carrinhos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Carrinhos::class, 'targetAttribute' => ['carrinhos_id' => 'id']],
            [['produtos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::class, 'targetAttribute' => ['produtos_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quantidade' => 'Quantidade',
            'preco_venda' => 'Preco Venda',
            'valor_iva' => 'Valor Iva',
            'subtotal' => 'Subtotal',
            'produtos_id' => 'Produtos ID',
            'carrinhos_id' => 'Carrinhos ID',
        ];
    }

    /**
     * Gets query for [[Carrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrinhos()
    {
        return $this->hasOne(Carrinhos::class, ['id' => 'carrinhos_id']);
    }

    /**
     * Gets query for [[Produtos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutos()
    {
        return $this->hasOne(Produtos::class, ['id' => 'produtos_id']);
    }

    /**
     * Gets query for [[LinhasFaturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasFaturas()
    {
        return $this->hasMany(LinhasFaturas::class, ['produtos_carrinhos_id' => 'id']);
    }
}
