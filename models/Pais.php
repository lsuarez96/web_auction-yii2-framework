<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pais".
 *
 * @property integer $id_pais
 * @property string $nombre_pais
 *
 * @property Usuario[] $usuarios
 */
class Pais extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pais';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_pais'], 'required'],
            [['nombre_pais'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_pais' => 'Id Pais',
            'nombre_pais' => 'Nombre Pais',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuario::className(), ['pais' => 'id_pais']);
    }
}
