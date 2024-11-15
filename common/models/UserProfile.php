<?php

namespace common\models;


use Carbon\Carbon;
use Yii;
use backend\models\AuthAssignment;

/**
 * This is the model class for table "user_profile".
 *
 * @property int $id
 * @property string|null $primeironome
 * @property string|null $apelido
 * @property string|null $codigopostal
 * @property string|null $localidade
 * @property string|null $rua
 * @property string|null $nif
 * @property string|null $dtanasc
 * @property string|null $dtaregisto
 * @property string|null $telefone
 * @property string|null $genero
 * @property int $user_id
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{

    const SCENARIO_USERPROFILE = 'userprofile';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['primeironome', 'apelido','dtanasc', 'dtaregisto','genero', 'user_id'], 'required'
                ,'message'=>'Este campo é obrigatório'],
            [['primeironome', 'apelido'], 'string', 'max' => 50],
            [['dtanasc', 'dtaregisto'], 'safe'],
            [['genero'], 'string'],
            [['codigopostal'], 'string', 'max' => 8],
            [['localidade', 'rua'], 'string', 'max' => 100],
            [['nif'], 'string', 'max' => 10, 'min' => 9, 'tooShort' => 'Precisa no mínimo 9 digitos', 'tooLong' => 'Não pode ter mais de 9 digitos'],
            [['nif'], 'unique'],
            [['nif'], 'match', 'pattern' => '/^\d+$/i', 'message' => 'Só são aceites números.'],
            [['telefone'], 'string', 'max' => 9, 'min' => 9, 'tooShort' => 'Precisa no mínimo 9 digitos', 'tooLong' => 'Não pode ter mais de 9 digitos'],
            [['telefone'], 'unique'],
            [['telefone'], 'match', 'pattern' => '/^\d+$/i', 'message' => 'Só são aceites números .'],
            [['user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['rua', 'codigopostal', 'localidade', 'telefone', 'nif','primeironome','apelido'], 'required','on' => self::SCENARIO_USERPROFILE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'primeironome' => 'Primeironome',
            'apelido' => 'Apelido',
            'codigopostal' => 'Codigopostal',
            'localidade' => 'Localidade',
            'rua' => 'Rua',
            'nif' => 'Nif',
            'dtanasc' => 'Dtanasc',
            'dtaregisto' => 'Dtaregisto',
            'telefone' => 'Telefone',
            'genero' => 'Genero',
            'user_id' => 'User ID',
            'email' => 'Email',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // Define a scenario for the password-related actions
        $scenarios[self::SCENARIO_USERPROFILE] = ['rua', 'codigopostal', 'localidade', 'telefone', 'nif','primeironome','apelido'];
        return $scenarios;
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

    public function getAuth()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'user_id']);
    }

    public function updateProfile()
    {

        $Profile = UserProfile::findOne(['id' => $this->id]);

        $Profile->primeironome = $this->primeironome;
        $Profile->apelido = $this->apelido;
        $Profile->codigopostal = $this->codigopostal;
        $Profile->localidade = $this->localidade;
        $Profile->rua = $this->rua;
        $Profile->nif = $this->nif;
        $Profile->dtanasc = $this->dtanasc;
        $Profile->dtaregisto = Carbon::now();
        $Profile->genero = $this->genero;
        $Profile->telefone = $this->telefone;


        return $Profile->save();
    }
}
