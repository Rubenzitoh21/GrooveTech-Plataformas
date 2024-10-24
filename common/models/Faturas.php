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
 * @property int $user_profile_id
 *
 * @property Expedicoes[] $expedicoes
 * @property LinhasFaturas[] $linhasFaturas
 * @property Pagamentos[] $pagamentos
 * @property UserProfile $userProfile
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
            [['data', 'valortotal', 'status', 'user_profile_id'], 'required'],
            [['data'], 'safe'],
            [['valortotal'], 'number'],
            [['user_profile_id'], 'integer'],
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
            'data' => 'Data',
            'valortotal' => 'Valortotal',
            'status' => 'Status',
            'user_profile_id' => 'User Profile ID',
        ];
    }

    /**
     * Gets query for [[Expedicoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpedicoes()
    {
        return $this->hasMany(Expedicoes::class, ['faturas_id' => 'id']);
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
        return $this->hasMany(Pagamentos::class, ['faturas_id' => 'id']);
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
