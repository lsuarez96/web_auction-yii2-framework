<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    public function __construct()
    {
        if (isset($_GET['id'])) {
            $temp_user = Usuario::findOne(['id_usuario' => $_GET['id']]);
            $username = $temp_user->nom_user;
            $password = $temp_user->clave;
        }
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            //[['username', 'password'], 'required'],
            [['username'], 'required', 'message' => 'Por favor introduzca su nombre de usuario o su correo'],
            [['password'], 'required', 'message' => 'Debe introducir una contraseña'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Usuario',
            'password' => 'Contraseña',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
//            var_dump(Usuario::findOne(['id_usuario'=>$user->id_usuario])->getDeshabilitados()->one());
//            die();
            if ($this->_user != null && $this->_user != false) {
                if (!Usuario::isActiveById($user->id_usuario)) {
                    $this->addError($attribute, 'Su cuenta ha sido deshabilitada !!!!!!!!');
                } else if ($this->_user != false && !$user->validatePassword($this->password)) {
                    $this->addError($attribute, 'Nombre de usuario o contraseña incorrectos !!!!!!!!');
                }else {
                    return true;
                }
            }else {
                $this->addError($attribute, 'Nombre de usuario o contraseña incorrectos !!!!!!!!');
                return false;
            }
        }
        return false;
    }


    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && $this->_user != false) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Usuario::findOne(['nom_user'=>$this->username]);
            if($this->_user===false||$this->_user==null){
                $this->_user = Usuario::findOne(['correo'=>$this->username]);
            }
            
        }

        return $this->_user;
    }
}
