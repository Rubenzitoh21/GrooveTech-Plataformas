<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categorias_produtos".
 *
 * @property int $id
 * @property string $nome
 * @property string|null $obs
 *
 * @property Produtos[] $produtos
 */
class CategoriasProdutos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categorias_produtos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'obs'], 'required', 'message' => 'Este campo não pode ficar em branco.'],
            [['nome'], 'string', 'max' => 50, 'tooLong' => 'Nome deve conter no máximo 50 caracteres.'],
            [['obs'], 'string', 'max' => 200, 'tooLong' => 'Observações deve conter no máximo 200 caracteres.'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'obs' => 'Observações',
        ];
    }

    /**
     * Gets query for [[Produtos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutos()
    {
        return $this->hasMany(Produtos::class, ['categorias_produtos_id' => 'id']);
    }
}
