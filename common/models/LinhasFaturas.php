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
 * @property int $avaliacoes_id
 *
 * @property Avaliacoes $avaliacoes
 * @property Faturas $faturas
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
            [['quantidade', 'preco_venda', 'valor_iva', 'subtotal', 'faturas_id', 'avaliacoes_id'], 'required'],
            [['preco_venda', 'valor_iva', 'subtotal'], 'number'],
            [['faturas_id', 'avaliacoes_id'], 'integer'],
            [['quantidade'], 'string', 'max' => 45],
            [['avaliacoes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Avaliacoes::class, 'targetAttribute' => ['avaliacoes_id' => 'id']],
            [['faturas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faturas::class, 'targetAttribute' => ['faturas_id' => 'id']],
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
            'faturas_id' => 'Faturas ID',
            'avaliacoes_id' => 'Avaliacoes ID',
        ];
    }

    /**
     * Gets query for [[Avaliacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacoes()
    {
        return $this->hasOne(Avaliacoes::class, ['id' => 'avaliacoes_id']);
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
}
