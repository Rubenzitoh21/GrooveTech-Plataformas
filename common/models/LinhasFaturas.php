<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "linhas_faturas".
 *
 * @property int $id
 * @property string $quantidade
 * @property float $preco_venda
 * @property float $valor_iva
 * @property float $subtotal
 * @property int $faturas_id
 * @property int $produtos_id
 *
 * @property Avaliacoes[] $avaliacoes
 * @property Faturas $faturas
 * @property Produtos $produtos
 */
class LinhasFaturas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'linhas_faturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantidade', 'preco_venda', 'valor_iva', 'subtotal', 'faturas_id', 'produtos_id'], 'required'],
            [['preco_venda', 'valor_iva', 'subtotal'], 'number'],
            [['faturas_id', 'produtos_id'], 'integer'],
            [['quantidade'], 'string', 'max' => 45],
            [['faturas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faturas::class, 'targetAttribute' => ['faturas_id' => 'id']],
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
            'preco_venda' => 'Preco Unitário',
            'valor_iva' => 'Valor do Iva',
            'subtotal' => 'Sub-total',
            'faturas_id' => 'Faturas',
            'produtos_id' => 'Produto',
        ];
    }

    /**
     * Gets query for [[Avaliacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacoes()
    {
        return $this->hasMany(Avaliacoes::class, ['linhas_faturas_id' => 'id']);
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasOne(Faturas::class, ['id' => 'faturas_id']);
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

}
