<?php

namespace common\models;

use Exception;
use Yii;
use common\models\Produtos;
use yii\imagine\Image;

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
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10, 'message' => 'Ficheiro inválido. Apenas são aceites imagens nos formatos png, jpg ou jpeg.'],
            [['produto_id'], 'required', 'message' => 'Tem de selecionar um produto para ser associado à imagem.'],
            [['produto_id'], 'integer'],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::class, 'targetAttribute' => ['produto_id' => 'id'], 'message' => 'O produto selecionado não existe.'],
        ];

    }

    public function upload()
    {
        $uploadPaths = [];

        if ($this->validate()) {

            foreach ($this->imageFiles as $file) {
                $uid = uniqid();
                $fileName = $uid . $file->baseName . '.' . $file->extension;

                // Caminhos para salvar a imagem
                $uploadPathBack = Yii::getAlias('@backend/web/images/') . $fileName;
                $uploadPathFront = Yii::getAlias('@frontend/web/images/') . $fileName;

                // Salva temporariamente a imagem original
                $file->saveAs($uploadPathBack, false);

                // Redimensiona/corta e salva diretamente sobrescrevendo o arquivo original
                Image::thumbnail($uploadPathBack, 500, 600)
                    ->save($uploadPathBack, ['quality' => 80]);

                // Copia a imagem processada para o diretório do frontend
                copy($uploadPathBack, $uploadPathFront);

                // Adiciona o caminho da imagem processada ao array de caminhos
                $uploadPaths[] = $uploadPathBack;
            }

            return $uploadPaths;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $filePathBackend = Yii::getAlias('@backend/web/images/') . $this->fileName;
        $filePathFrontend = Yii::getAlias('@frontend/web/images/') . $this->fileName;

        try {
            if (file_exists($filePathBackend)) {
                unlink($filePathBackend);
            }

            if (file_exists($filePathFrontend)) {
                unlink($filePathFrontend);
            }
        } catch (Exception $e) {
            Yii::error("Erro ao excluir arquivo: " . $e->getMessage(), __METHOD__);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fileName' => 'Nome do Ficheiro',
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
