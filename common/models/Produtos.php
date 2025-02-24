<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "produtos".
 *
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property float $preco
 * @property string|null $obs
 * @property int $categorias_produtos_id
 * @property int $ivas_id
 *
 * @property CategoriasProdutos $categoriasProdutos
 * @property Imagens[] $imagens
 * @property Ivas $ivas
 * @property ProdutosCarrinhos[] $produtosCarrinhos
 */
class Produtos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produtos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'descricao', 'preco', 'categorias_produtos_id', 'ivas_id'], 'required', 'message' => 'Este campo não pode ficar em branco.'],
            [['preco'], 'number'],
            [['categorias_produtos_id', 'ivas_id'], 'integer'],
            [['nome'], 'string', 'max' => 50],
            [['descricao'], 'string', 'max' => 200],
            [['obs'], 'string', 'max' => 200],
            [['nome'], 'unique'],
            [['categorias_produtos_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriasProdutos::class, 'targetAttribute' => ['categorias_produtos_id' => 'id']],
            [['ivas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ivas::class, 'targetAttribute' => ['ivas_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'descricao' => 'Descrição',
            'preco' => 'Preço',
            'obs' => 'Observações',
            'categorias_produtos_id' => 'Categoria',
            'ivas_id' => 'Iva (%)',
        ];
    }

    /**
     * Gets query for [[CategoriasProdutos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriasProdutos()
    {
        return $this->hasOne(CategoriasProdutos::class, ['id' => 'categorias_produtos_id']);
    }

    /**
     * Gets query for [[Imagens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImagens()
    {
        return $this->hasMany(Imagens::class, ['produto_id' => 'id']);
    }

    /**
     * Gets query for [[Ivas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIvas()
    {
        return $this->hasOne(Ivas::class, ['id' => 'ivas_id']);
    }

    /**
     * Gets query for [[ProdutosCarrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutosCarrinhos()
    {
        return $this->hasMany(ProdutosCarrinhos::class, ['produtos_id' => 'id']);
    }

    /**
     * Gets query for [[LinhasFaturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasFaturas()
    {
        return $this->hasMany(LinhasFaturas::class, ['produtos_id' => 'id']);
    }

    /**
     * Gets query for [[Avaliacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacoes()
    {
        return $this->hasMany(Avaliacoes::class, ['linhas_faturas_id' => 'id'])
            ->via('linhasFaturas');
    }
}
