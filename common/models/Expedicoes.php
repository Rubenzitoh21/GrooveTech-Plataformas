<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "expedicoes".
 *
 * @property int $id
 * @property string $metodoexp
 *
 * @property Faturas[] $faturas
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
            [['metodoexp'], 'required'],
            [['metodoexp'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'metodoexp' => 'Metodo de Expedição',
        ];
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Faturas::class, ['expedicoes_id' => 'id']);
    }
}
