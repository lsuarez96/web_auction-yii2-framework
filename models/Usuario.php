<?php

namespace app\models;

use Yii;
use yii\base\InvalidParamException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuario".
 *
 * @property integer $id_usuario
 * @property string $nom_user
 * @property string $nombre
 * @property string $apellido
 * @property string $correo
 * @property integer $pais
 * @property string $clave
 * @property boolean $borr_log
 *
 * @property Deshabilitado[] $deshabilitados
 * @property Notificaciones[] $notificaciones
 * @property Producto[] $productos
 * @property RolUser[] $rolUsers
 * @property Rol[] $rols
 * @property Subasta[] $subastas
 * @property SubastaSeguida[] $subastaSeguidas
 * @property Subasta[] $subastas0
 * @property Pais $pais0
 */
class Usuario extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{


    public $claverepeat;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['nom_user', 'correo', 'clave'], 'required'],
            [['nom_user'],'required','message' =>'Por favor introduzca su nickname'],
            [['correo'],'required','message' =>'Por favor especifique su correo de contacto'],
            [['clave'],'required','message' =>'Por favor especifique una contraseña para su cuenta'],
            [['claverepeat'],'required','message' =>'Por favor confirme su contraseña'],
            [['nombre'],'required','message' =>'Por favor introduzca su nombre'],
            [['apellido'],'required','message' =>'Por favor introduzca su(s) apellido(s)'],
            [['pais'], 'integer'],
            [['borr_log'], 'boolean'],
            [['nom_user', 'nombre', 'apellido', 'correo', 'clave'], 'string', 'max' => 255],
            [['nom_user'], 'unique', 'message' => 'El nombre de usuario ya existe, modifiquelo'],
            [['correo'], 'unique', 'message' => 'Este correo ya esta en uso por otro usuario'],
            [['pais'], 'exist', 'skipOnError' => true, 'targetClass' => Pais::className(), 'targetAttribute' => ['pais' => 'id_pais']],
            [['correo'], 'email', 'message' => 'Inserte una direccion de correo valida'],
            [['claverepeat'], 'compare', 'compareAttribute' => 'clave', 'message' => 'Las contraseñas tienen que ser iguales'],
            [['auth_key'],'string','max'=>255],
        ];
    }
//    public function scenarios()
//    {
//        return [
//            'crear' => ['nom_user','correo','clave','pais',''],
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'nom_user' => 'Usuario',
            'nombre' => 'Nombre',
            'apellido' => 'Apellidos',
            'correo' => 'Correo',
            'pais' => 'Pais',
            'clave' => 'Contraseña',
            'claverepeat' => 'Repita la Contraseña',
            'auth_key'=>'',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeshabilitados()
    {
        return $this->hasMany(Deshabilitado::className(), ['usuario' => 'id_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificaciones()
    {
        return $this->hasMany(Notificaciones::className(), ['usuario_id' => 'id_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductos()
    {
        return $this->hasMany(Producto::className(), ['id_usuario' => 'id_usuario']);
    }

  

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubastas()
    {
        return $this->hasMany(Subasta::className(), ['id_usuario' => 'id_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubastaSeguidas()
    {
        return $this->hasMany(SubastaSeguida::className(), ['usuario' => 'id_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubastas0()
    {
        return $this->hasMany(Subasta::className(), ['id_subasta' => 'subasta_id'])->viaTable('subasta_seguida', ['usuario' => 'id_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPais0()
    {
        return $this->hasOne(Pais::className(), ['id_pais' => 'pais']);
    }

    public function validatePassword($password)
    {
        $validPassword = false;
        try {
            $validPassword = Yii::$app->getSecurity()->validatePassword($password, $this->clave);
        }catch(InvalidParamException $e){

        }
        return $validPassword;
    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        return self::findOne(['id_usuario' => $id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->id_usuario;
    }

//    /**
//     * Finds user by password reset token
//     *
//     * @param string $token password reset token
//     * @return static|null
//     */
//    public static function findByPasswordResetToken($token)
//    {
//        if (!static::isPasswordResetTokenValid($token)) {
//            return null;
//        }
//
//        return static::findOne([
//            'password_reset_token' => $token,
//            'status' => self::STATUS_ACTIVE,
//        ]);
//    }

//    /**
//     * Finds out if password reset token is valid
//     *
//     * @param string $token password reset token
//     * @return boolean
//     */
//    public static function isPasswordResetTokenValid($token)
//    {
//        if (empty($token)) {
//            return false;
//        }
//        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
//        $parts = explode('_', $token);
//        $timestamp = (int) end($parts);
//        return $timestamp + $expire >= time();
//    }


//    /**
//     * @inheritdoc
//     */
//    public function getAuthKey()
//    {
//        return $this->auth_key;
//    }

//    /**
//     * @inheritdoc
//     */
//    public function validateAuthKey($authKey)
//    {
//        return $this->getAuthKey() === $authKey;
//    }
//
//
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

//    /**
//     * Generates new password reset token
//     */
//    public function generatePasswordResetToken()
//    {
//        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
//    }

    /**
     * Removes password reset token
     */
//    public function removePasswordResetToken()
//    {
//        $this->password_reset_token = null;
//    }

    /*buscar si el rol del usuario esta en el arreglo pasado*/
    public static function roleInArray($arr_role)
    {
        if(isset(Yii::$app->user->id)) {
            if(Yii::$app->user->id!=null) {
                $find_role = AuthAssignment::find()->where(['user_id' => Yii::$app->user->id])->one();
                $role = $find_role->item_name;
                return in_array($role, $arr_role);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /*preguntar si el usuario esta activo*/
    public static function isActive()
    { 
        return Usuario::findOne(['id_usuario' => Yii::$app->user->id])->borr_log == false;
    }


    /*preguntar si el usuario esta activo*/
    public static function isActiveById($id)
    {
    	// var_dump(Usuario::findOne(['id_usuario' =>$id])->borr_log );
    	// die();
        return Usuario::findOne(['id_usuario' =>$id])->borr_log == false && Deshabilitado::findOne(['usuario' =>$id]) == null;
    }
    /*devolver el rol */
    public function getRole()
    {
        $find_role = AuthAssignment::find()->where(['user_id' => Yii::$app->user->id])->one();
        $role = $find_role->item_name;
        return $role;
    }

//    public static function getRole($id){
//        return AuthAssignment::findOne(['user_id'=>$id])->item_name;
//    }
    public static function calcularReputacion($id)
    {
        $terminadas = count(Subasta::findAll(['id_usuario' => $id, 'terminada' => true]));
        $pujadas = count(Subastaseguida::findBySql('Select subasta_seguida.* from subasta_seguida JOIN subasta on subasta_seguida.subasta_id=subasta.id_subasta WHERE subasta.terminada=true AND subasta.id_usuario!=' . $id)->all());
        $porcientoCompra = $pujadas != 0 ? (($terminadas / $pujadas) * 100) : 100;
        $compradas = count(Subasta::findBySql('Select subasta.* from subasta join producto on subasta.id_producto=producto.id_producto WHERE producto.id_usuario=' . $id . ' and subasta.terminada=true and subasta.id_usuario!=' . $id)->all());
        $puestas = count(Producto::findAll(['id_usuario' => $id]));
        $porcientoVenta = $puestas != 0 ? (($compradas / $puestas) * 100) : 100;
        $rep = ($porcientoCompra + $porcientoVenta) / 2;
        $rep = $rep == 0 ? 100 : $rep;
        switch ($rep) {
            case $rep < 51:
                return 'Mala';
            case $rep < 81:
                return 'Confiable';
            case $rep < 101:
                return 'Excelente';
        }

    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key===$authKey;
    }

    public static function getUsuariosDeshabilitados(){
       return \app\models\Usuario::findBySql("Select DISTINCT usuario.* from usuario join deshabilitado on usuario.id_usuario=deshabilitado.usuario WHERE tiempo>now()")->all();

    }


    public static  function getExitosos(){
        return \app\models\Usuario::findBySql('Select DISTINCT usuario.* from usuario join producto on producto.id_usuario=usuario.id_usuario JOIN subasta on subasta.id_producto=producto.id_producto WHERE subasta.terminada=true ORDER BY COUNT(subasta.id_subasta)')->all();

    }
    
    
}
