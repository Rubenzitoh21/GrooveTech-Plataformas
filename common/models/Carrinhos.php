<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "carrinhos".
 *
 * @property int $id
 * @property string $dtapedido
 * @property string $metodo_envio
 * @property string $status
 * @property float $valortotal
 * @property int $user_profile_id
 *
 * @property ProdutosCarrinhos[] $produtosCarrinhos
 * @property UserProfile $userProfile
 */
class Carrinhos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrinhos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dtapedido', 'metodo_envio', 'status', 'valortotal', 'user_profile_id'], 'required'],
            [['dtapedido'], 'safe'],
            [['valortotal'], 'number'],
            [['user_profile_id'], 'integer'],
            [['metodo_envio'], 'string', 'max' => 45],
            [['status'], 'string', 'max' => 50],
            [['user_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserProfile::class, 'targetAttribute' => ['user_profile_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dtapedido' => 'Dtapedido',
            'metodo_envio' => 'Metodo Envio',
            'status' => 'Status',
            'valortotal' => 'Valortotal',
            'user_profile_id' => 'User Profile ID',
        ];
    }

    /**
     * Gets query for [[ProdutosCarrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutosCarrinhos()
    {
        return $this->hasMany(ProdutosCarrinhos::class, ['carrinhos_id' => 'id']);
    }

    /**
     * Gets query for [[UserProfile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['id' => 'user_profile_id']);
    }
}
