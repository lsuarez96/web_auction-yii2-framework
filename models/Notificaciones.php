<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notificaciones".
 *
 * @property integer $id_notificaciones
 * @property integer $usuario_id
 * @property string $nota
 * @property boolean $nuevo
 *
 * @property Usuario $usuario
 */
class Notificaciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notificaciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuario_id', 'nota'], 'required'],
            [['usuario_id'], 'integer'],
            [['nota'], 'string'],
            [['nuevo'], 'boolean'],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario_id' => 'id_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_notificaciones' => 'Id Notificaciones',
            'usuario_id' => 'Usuario ID',
            'nota' => 'Nota',
            'nuevo' => 'Nuevo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['id_usuario' => 'usuario_id']);
    }
}
