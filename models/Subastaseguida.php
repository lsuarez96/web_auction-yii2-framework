<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subasta_seguida".
 *
 * @property integer $id_subasta_seguida
 * @property integer $usuario
 * @property integer $subasta_id
 *
 * @property Usuario $usuario0
 * @property Subasta $subasta
 */
class Subastaseguida extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subasta_seguida';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuario', 'subasta_id'], 'required'],
            [['usuario', 'subasta_id'], 'integer'],
            [['usuario', 'subasta_id'], 'unique', 'targetAttribute' => ['usuario', 'subasta_id'], 'message' => 'The combination of Usuario and Subasta ID has already been taken.'],
            [['usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario' => 'id_usuario']],
            [['subasta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subasta::className(), 'targetAttribute' => ['subasta_id' => 'id_subasta']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_subasta_seguida' => 'Id Subasta Seguida',
            'usuario' => 'Usuario',
            'subasta_id' => 'Subasta ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario0()
    {
        return $this->hasOne(Usuario::className(), ['id_usuario' => 'usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubasta()
    {
        return $this->hasOne(Subasta::className(), ['id_subasta' => 'subasta_id']);
    }

    public static function getFollowedByUser($id_user){
            return static::find()->where(['usuario'=>$id_user])->orderBy(['subasta_id'=>SORT_DESC])->all();
    }
}
