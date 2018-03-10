<?php
/**
 * Created by PhpStorm.
 * User: Luisito Suarez
 * Date: 4/12/2017
 * Time: 11:48
 */

namespace app\models;


use yii\base\Model;

class ModifyUserModel extends Model
{
    public $nom_user;
    public $nombre;
    public $apellido;
    public $clave;
    public $correo;
    public $pais;
    public $claverepeat;
    public $claveOld;

    public function rules()
    {
        return [
            //[['nom_user', 'correo', 'clave'], 'required'],
            [['nom_user'],'required','message' =>'Por favor introduzca su nickname'],
            [['correo'],'required','message' =>'Por favor especifique su correo de contacto'],
            //[['clave'],'required','message' =>'Por favor especifique una contraseña para su cuenta'],
            //[['claverepeat'],'required','message' =>'Por favor confirme su contraseña'],
            [['nombre'],'required','message' =>'Por favor introduzca su nombre'],
            [['apellido'],'required','message' =>'Por favor introduzca su(s) apellido(s)'],
            [['pais'], 'integer'],

            [['nom_user', 'nombre', 'apellido', 'correo', 'clave'], 'string', 'max' => 255],
            [['nom_user'], 'unique', 'message' => 'El nombre de usuario ya existe, modifiquelo'],
            [['correo'], 'unique', 'message' => 'Este correo ya esta en uso por otro usuario'],
            [['pais'], 'exist', 'skipOnError' => true, 'targetClass' => Pais::className(), 'targetAttribute' => ['pais' => 'id_pais']],
            [['correo'], 'email', 'message' => 'Inserte una direccion de correo valida'],
            [['claverepeat'], 'compare', 'compareAttribute' => 'clave', 'message' => 'Las contraseñas tienen que ser iguales'],
            [['claveOld'], 'string'],
           
        ];
    }

    public function attributeLabels()
    {
        return [

            'nom_user' => 'Usuario',
            'nombre' => 'Nombre',
            'apellido' => 'Apellidos',
            'correo' => 'Correo',
            'pais' => 'Pais',
            'claveOld' => 'Contraseña',
            'clave' => 'Contraseña Nueva',
            'claverepeat' => 'Repita la Contraseña',
        ];
    }


}
