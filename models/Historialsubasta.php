<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historial_subasta".
 *
 * @property integer $id_historial_subasta
 * @property integer $id_subasta
 * @property integer $id_usuario
 * @property double $precio
 *
 * @property Subasta $idSubasta
 * @property Usuario $idUsuario
 */
class Historialsubasta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'historial_subasta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_subasta', 'id_usuario', 'precio'], 'required'],
            [['id_subasta', 'id_usuario'], 'integer'],
            [['precio'], 'number'],
            [['id_subasta', 'precio'], 'unique', 'targetAttribute' => ['id_subasta', 'precio'], 'message' => 'The combination of Id Subasta and Precio has already been taken.'],
            [['id_subasta'], 'exist', 'skipOnError' => true, 'targetClass' => Subasta::className(), 'targetAttribute' => ['id_subasta' => 'id_subasta']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_historial_subasta' => 'Id Historial Subasta',
            'id_subasta' => 'Id Subasta',
            'id_usuario' => 'Id Usuario',
            'precio' => 'Precio',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSubasta()
    {
        return $this->hasOne(Subasta::className(), ['id_subasta' => 'id_subasta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsuario()
    {
        return $this->hasOne(Usuario::className(), ['id_usuario' => 'id_usuario']);
    }
}
