<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "avaliacoes".
 *
 * @property int $id
 * @property string $comentario
 * @property string $dtarating
 * @property int $rating
 * @property int $user_id
 * @property int $linhas_faturas_id
 *
 * @property LinhasFaturas $linhasFaturas
 * @property User $user
 */
class Avaliacoes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'avaliacoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comentario', 'dtarating', 'rating', 'user_id', 'linhas_faturas_id'], 'required', 'message' => 'Este campo não pode ficar em branco.'],
            [['dtarating'], 'safe'],
            [['rating', 'user_id', 'linhas_faturas_id'], 'integer'],
            [['comentario'], 'string', 'max' => 200],
            [['linhas_faturas_id'], 'exist', 'skipOnError' => true, 'targetClass' => LinhasFaturas::class, 'targetAttribute' => ['linhas_faturas_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comentario' => 'Comentário',
            'dtarating' => 'Data da avaliação',
            'rating' => 'Classificação',
            'user_id' => 'User ID',
            'linhas_faturas_id' => 'Linhas Faturas ID',
        ];
    }

    /**
     * Gets query for [[LinhasFaturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasFaturas()
    {
        return $this->hasOne(LinhasFaturas::class, ['id' => 'linhas_faturas_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
