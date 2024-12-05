<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pagamentos".
 *
 * @property int $id
 * @property string $metodopag
 *
 * @property Faturas[] $faturas
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
            [['metodopag'], 'required', 'message' => 'Este campo não pode ficar em branco.'],
            [['metodopag'], 'string', 'max' => 45],
        ];

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'metodopag' => 'Método de Pagamento',
        ];
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Faturas::class, ['pagamentos_id' => 'id']);
    }
}
