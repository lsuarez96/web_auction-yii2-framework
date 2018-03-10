<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subasta".
 *
 * @property integer $id_subasta
 * @property integer $id_producto
 * @property integer $id_usuario
 * @property double $precio_actual
 * @property integer $actividad
 * @property boolean $terminada
 * @property boolean $notificada
 * @property Usuario $idUsuario
 * @property Producto $idProducto
 * @property SubastaSeguida[] $subastaSeguidas
 * @property Usuario[] $usuarios
 */
class Subasta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subasta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_producto','precio_actual','actividad'], 'required'],
            [['id_producto', 'id_usuario', 'actividad'], 'integer'],
            [['precio_actual'], 'number'],
            [['terminada'], 'boolean'],
            [['notificada'],'boolean'],
            [['id_producto'], 'unique'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
            [['id_producto'], 'exist', 'skipOnError' => true, 'targetClass' => Producto::className(), 'targetAttribute' => ['id_producto' => 'id_producto']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_subasta' => 'Id Subasta',
            'id_producto' => 'Id Producto',
            'id_usuario' => 'Id Usuario',
            'precio_actual' => 'Precio Actual',
            'actividad' => 'Actividad del producto',
            'terminada' => 'Terminada',
            'notificada'=>'',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsuario()
    {
        return $this->hasOne(Usuario::className(), ['id_usuario' => 'id_usuario']);
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
    public function getSubastaSeguidas()
    {
        return $this->hasMany(SubastaSeguida::className(), ['subasta_id' => 'id_subasta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuario::className(), ['id_usuario' => 'usuario'])->viaTable('subasta_seguida', ['subasta_id' => 'id_subasta']);
    }
    
}
