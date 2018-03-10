<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "foto".
 *
 * @property integer $id_foto
 * @property string $url
 * @property string $descripcion
 * @property integer $producto
 *
 * @property Producto $producto0
 */
class Foto extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'producto'], 'required'],
            [['producto'], 'integer'],
            [['file'], 'file'],
            [['url', 'descripcion'], 'string', 'max' => 255],
            [['producto'], 'exist', 'skipOnError' => true, 'targetClass' => Producto::className(), 'targetAttribute' => ['producto' => 'id_producto']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_foto' => 'Id Foto',
            'url' => 'Localizacion',
            'descripcion' => 'Descripcion',
            'producto' => 'Producto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducto0()
    {
        return $this->hasOne(Producto::className(), ['id_producto' => 'producto']);
    }

    public static function findFirstProductFoto($product)
    {

        return Foto::findOne(['producto'=>$product]);
    }

    public static function findFirstProductFotoSlide($product){
        foreach (Foto::findAll(['producto' => $product]) as $foto) {
            $url=$foto->url;
            $size=getimagesize($url);
            if($size[0]-$size[1]>-250){
                return $foto;
            }
        }
        $foto=new Foto();
        $foto->producto=$product;
        $foto->url='images/logo.png';
        return $foto;
    }
}
