<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ivas".
 *
 * @property int $id
 * @property int $percentagem
 * @property string $descricao
 * @property int $vigor
 *
 * @property Produtos[] $produtos
 */
class Ivas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ivas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['percentagem', 'descricao', 'vigor'], 'required', 'message' => 'Este campo não pode ficar em branco.'],
            [['percentagem', 'vigor'], 'integer', 'message' => 'Este campo deve conter um número inteiro.'],
            [['descricao'], 'string', 'max' => 80, 'tooLong' => 'Este campo deve conter no máximo 80 caracteres.'],
        ];

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'percentagem' => 'Percentagem (%)',
            'descricao' => 'Descrição',
            'vigor' => 'Vigor',
        ];
    }

    /**
     * Gets query for [[Produtos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutos()
    {
        return $this->hasMany(Produtos::class, ['ivas_id' => 'id']);
    }
}
