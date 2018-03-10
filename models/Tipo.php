<?php

namespace app\models;

use Yii;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "tipo".
 *
 * @property integer $id_tipo
 * @property string $nom_tipo
 *
 * @property SubTipo[] $subTipos
 */
class Tipo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nom_tipo'], 'required'],
            [['nom_tipo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo' => 'Id Tipo',
            'nom_tipo' => 'Nom Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubTipos()
    {
        return $this->hasMany(SubTipo::className(), ['id_tipo' => 'id_tipo']);
    }
    public function getProducto(){
        return $this->hasMany(Producto::className(),['tipo'=>'id_tipo']);

    }
}
