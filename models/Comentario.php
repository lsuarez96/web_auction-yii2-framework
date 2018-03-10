<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comentario".
 *
 * @property integer $id_comentario
 * @property integer $id_usuario
 * @property integer $id_producto
 * @property string $comentario
 *
 * @property Producto $idProducto
 * @property Usuario $idUsuario
 */
class Comentario extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comentario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_producto', 'comentario'], 'required'],
            [['id_usuario', 'id_producto'], 'integer'],
            [['comentario'], 'string'],
            [['id_producto'], 'exist', 'skipOnError' => true, 'targetClass' => Producto::className(), 'targetAttribute' => ['id_producto' => 'id_producto']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_comentario' => 'Id Comentario',
            'id_usuario' => 'Id Usuario',
            'id_producto' => 'Id Producto',
            'comentario' => 'Comentario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProducto()
    {
        return $this->hasOne(Producto::className(), ['id_producto' => 'id_producto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsuario()
    {
        return $this->hasOne(Usuario::className(), ['id_usuario' => 'id_usuario']);
    }
}
