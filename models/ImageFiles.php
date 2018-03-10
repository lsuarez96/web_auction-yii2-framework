<?php
namespace app\models;
use yii\base\Model;


class ImageFiles extends Model
{
    public $file;

    public function rules()
    {
        return [
            [['file'],
                'file',
                'extensions' => 'jpg,png,jpeg',
                'maxFiles' => 8,
                'maxSize' => 5*1048576,/*1Mb*/
                'minSize' => 1024,
                'tooBig' => 'El tamaño de la imagen no puede exceder los 5MB',
                'tooMany' => 'Solo se pueden subir hasta 8 fotos',
                'tooSmall' => 'El tamaño de la imagen no puede ser inferior a 1kb',
                'uploadRequired' => 'Suba imagenes del producto',
                'skipOnEmpty' => false,
                'wrongExtension' => 'Solo se deben subir fotos en formato *.jpg,*.jpeg o *.png',
                'on' => 'article'
            ],
            [['file'],
                'file',
                'extensions' => 'jpg,png,jpeg',
                'maxFiles' => 1,
                'maxSize' => 5*1048576,/*1Mb*/
                'minSize' => 1024,
                'tooBig' => 'Archivo demasiado grande',
                'tooMany' => 'Solo se pueden subir hasta 4 fotos',
                'tooSmall' => 'Imagen demasiado pequeña',
                'uploadRequired' => 'Suba imagenes del producto',
                'skipOnEmpty' => false,
                'wrongExtension' => 'Solo se deben subir fotos en formato *.jpg,*.jpeg o *.png',
                'on' => 'user'
            ]
        ];
    }

    public function attributelabels()
    {
        return [
            'file' => 'Imagenes del producto'
        ];
    }

    public function scenarios()
    {
        return [
            'article' => ['file'],
        ];
    }
}

?>