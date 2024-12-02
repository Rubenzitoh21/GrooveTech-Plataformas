<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "faturas".
 *
 * @property int $id
 * @property string $data
 * @property float $valortotal
 * @property string $status
 * @property int $user_id
 * @property int $pagamentos_id
 * @property int $expedicoes_id
 *
 * @property Expedicoes $expedicoes
 * @property LinhasFaturas[] $linhasFaturas
 * @property Pagamentos $pagamentos
 * @property User $user
 */
class Faturas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'valortotal', 'status', 'user_id', 'pagamentos_id', 'expedicoes_id'], 'required'],
            [['data'], 'safe'],
            [['valortotal'], 'number'],
            [['user_id', 'pagamentos_id', 'expedicoes_id'], 'integer'],
            [['status'], 'string', 'max' => 50],
            [['expedicoes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Expedicoes::class, 'targetAttribute' => ['expedicoes_id' => 'id']],
            [['pagamentos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pagamentos::class, 'targetAttribute' => ['pagamentos_id' => 'id']],
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
            'data' => 'Data',
            'valortotal' => 'Valortotal',
            'status' => 'Status',
            'user_id' => 'User ID',
            'pagamentos_id' => 'Pagamentos ID',
            'expedicoes_id' => 'Expedicoes ID',
        ];
    }

    /**
     * Gets query for [[Expedicoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpedicoes()
    {
        return $this->hasOne(Expedicoes::class, ['id' => 'expedicoes_id']);
    }

    /**
     * Gets query for [[LinhasFaturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasFaturas()
    {
        return $this->hasMany(LinhasFaturas::class, ['faturas_id' => 'id']);
    }

    /**
     * Gets query for [[Pagamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPagamentos()
    {
        return $this->hasOne(Pagamentos::class, ['id' => 'pagamentos_id']);
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
