<?php

namespace common\models;

use Yii;
use common\models\Produtos;

/**
 * This is the model class for table "imagens".
 *
 * @property int $id
 * @property string $fileName
 * @property int $produto_id
 *
 * @property Produtos $produto
 */
class Imagens extends \yii\db\ActiveRecord
{
    public $imageFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'imagens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'safe'],
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10],
            [['produto_id'], 'required', 'message' => 'Tem de selecionar um produto para ser associado à imagem'],
            [['produto_id'], 'integer'],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::class, 'targetAttribute' => ['produto_id' => 'id']],
        ];
    }

    public function upload()
    {
        $uploadPaths = [];

        if ($this->validate()) {

            foreach ($this->imageFiles as $file) {
                $uid = uniqid();
                $uploadPathBack = Yii::getAlias('@backend/web/public/produtos/') . $uid . $file->baseName . '.' . $file->extension;
                $uploadPathFront = Yii::getAlias('@frontend/web/public/produtos/') . $uid . $file->baseName . '.' . $file->extension;

                $file->saveAs($uploadPathBack, false);
                $file->saveAs($uploadPathFront, false);
                $uploadPaths[] = $uploadPathBack;

            }
            return $uploadPaths;
        } else {
            return false;
        }
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fileName' => 'Imagems',
            'produto_id' => 'Id do Produto',
        ];
    }

    /**
     * Gets query for [[Produto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduto()
    {
        return $this->hasOne(Produtos::class, ['id' => 'produto_id']);
    }
}
