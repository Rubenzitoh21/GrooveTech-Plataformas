<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "expedicoes".
 *
 * @property int $id
 * @property string $descricao
 * @property float $valor
 * @property int $faturas_id
 *
 * @property Faturas $faturas
 */
class Expedicoes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expedicoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao', 'valor', 'faturas_id'], 'required'],
            [['valor'], 'number'],
            [['faturas_id'], 'integer'],
            [['descricao'], 'string', 'max' => 45],
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
            'descricao' => 'Descricao',
            'valor' => 'Valor',
            'faturas_id' => 'Faturas ID',
        ];
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
