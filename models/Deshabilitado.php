<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deshabilitado".
 *
 * @property integer $id_deshabilitado
 * @property integer $usuario
 * @property string $tiempo
 * @property string $razon
 *
 * @property Usuario $usuario0
 */
class Deshabilitado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deshabilitado';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuario', 'tiempo', 'razon'], 'required'],
            [['usuario'], 'integer'],
            [['tiempo'], 'safe'],
            [['razon'], 'string', 'max' => 255],
            [['usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario' => 'id_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_deshabilitado' => 'Id Deshabilitado',
            'usuario' => 'Usuario',
            'tiempo' => 'Tiempo',
            'razon' => 'Razon',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario0()
    {
        return $this->hasOne(Usuario::className(), ['id_usuario' => 'usuario']);
    }
}
