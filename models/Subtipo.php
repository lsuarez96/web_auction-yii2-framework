<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sub_tipo".
 *
 * @property integer $id_sub_tipo
 * @property integer $id_tipo
 * @property string $sub_tipo
 *
 * @property Producto[] $productos
 * @property Tipo $idTipo
 */
class Subtipo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sub_tipo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tipo', 'sub_tipo'], 'required'],
            [['id_tipo'], 'integer'],
            [['sub_tipo'], 'string', 'max' => 255],
            [['id_tipo', 'sub_tipo'], 'unique', 'targetAttribute' => ['id_tipo', 'sub_tipo'], 'message' => 'The combination of Id Tipo and Sub Tipo has already been taken.'],
            [['id_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Tipo::className(), 'targetAttribute' => ['id_tipo' => 'id_tipo']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_sub_tipo' => 'Id Sub Tipo',
            'id_tipo' => 'Id Tipo',
            'sub_tipo' => 'Sub Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductos()
    {
        return $this->hasMany(Producto::className(), ['sub_tipo' => 'id_sub_tipo', 'tipo' => 'id_tipo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipo()
    {
        return $this->hasOne(Tipo::className(), ['id_tipo' => 'id_tipo']);
    }
}
