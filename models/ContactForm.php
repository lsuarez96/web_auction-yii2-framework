<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
           // name, email, subject and body are required
           // [['name', 'email', 'subject', 'body'], 'required'],
           Yii::$app->user->isGuest? [['name'],'required','message' => 'Por favor introduzca su nombre']:[['name'],'safe'],
            ['email', 'email','message' => 'Por favor introduzca una direccion valida'],
            Yii::$app->user->isGuest?[['email'],'required','message' => 'Por favor introduzca su direccion de correo']:[['email'],'safe'],
            [['subject'],'required','message' => 'Por favor introduzca un asunto'],
            [['body'],'required','message' => 'Por favor introduzca su mensage'],
            // email has to be a valid email address

            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha','message'=>'Por favor introduzca correctamente el codigo'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Codigo de verificacion',
            'name'=>'Nombre',
            'email'=>'Correo',
            'subject'=>'Asunto',
            'body'=>'Texto',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {

        if ($this->validate()) {
            if(!Yii::$app->params['isOffline']) {
                Yii::$app->mailer->compose()
                    ->setTo($email)
                    ->setFrom([$this->email => $this->name])
                    ->setSubject($this->subject)
                    ->setTextBody($this->body)
                    ->send();
            }
            return true;
        }
        return false;
    }
}
