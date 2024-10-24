<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pagamentos".
 *
 * @property int $id
 * @property string $metodopag
 * @property float $valor
 * @property string $data
 * @property int $faturas_id
 *
 * @property Faturas $faturas
 */
class Pagamentos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pagamentos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['metodopag', 'valor', 'data', 'faturas_id'], 'required'],
            [['valor'], 'number'],
            [['data'], 'safe'],
            [['faturas_id'], 'integer'],
            [['metodopag'], 'string', 'max' => 45],
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
            'metodopag' => 'Metodopag',
            'valor' => 'Valor',
            'data' => 'Data',
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
