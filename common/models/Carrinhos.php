<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "carrinhos".
 *
 * @property int $id
 * @property string $dtapedido
 * @property string $status
 * @property float $valortotal
 * @property int $user_id
 *
 * @property ProdutosCarrinhos[] $produtosCarrinhos
 * @property User $user
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
            [['dtapedido', 'status', 'valortotal', 'user_id'], 'required'],
            [['dtapedido'], 'safe'],
            [['valortotal'], 'number'],
            [['user_id'], 'integer'],
            [['status'], 'string', 'max' => 50],
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
            'dtapedido' => 'Data do Pedido',
            'status' => 'Status',
            'valortotal' => 'Valor total',
            'user_id' => 'User ID',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function getTotalProdutosCarrinho()
    {
        if (Yii::$app->user->isGuest) {
            return 0;
        }

        $carrinho = self::findOne(['user_id' => Yii::$app->user->id, 'status' => 'Ativo']);

        if (!$carrinho) {
            return 0;
        }

        return ProdutosCarrinhos::find()
            ->where(['carrinhos_id' => $carrinho->id])
            ->count();
    }
}
