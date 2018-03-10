<?php

namespace app\controllers;

use app\models\AuthAssignment;
use app\models\Deshabilitado;
use app\models\LoginForm;
use app\models\ModifyUserModel;
use app\models\Usuario;

use Yii;
use Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


//use yii2tech\html2pdf\Manager;

/**
 * UsuarioController implements the CRUD actions for Usuario model.
 */
class UsuarioController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['view', 'visit-profile', 'perfil', 'update', 'deshabilitar', 'delete', 'create-admin'],
                'rules' => [
                    ['allow' => true,
                        'actions' => ['visit-profile', 'perfil'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['@']
                    ],
                    ['allow' => true, //Yii::$app->user->can('user'),
                        'actions' => ['deshabilitar', 'delete', 'view', 'habilitar', 'eliminar', 'create-admin'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rules, $action) {
                            $valid_roles = ['Administrador'];
                            return Usuario::roleInArray($valid_roles) && Usuario::isActive();
                        },
                    ],
                ],
            ],

        ];
    }


    /**
     * Lists all Usuario models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = Usuario::find()->where(['borr_log' => false])->all();

        return $this->render('index', [
            'data' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usuario model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Usuario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Usuario();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $pass=$model->clave;
            $model->clave = Yii::$app->getSecurity()->generatePasswordHash($model->clave);
            $model->auth_key = $this->randKey('abcdefg123456789', 200);
            $offline = Yii::$app->params['isOffline'];
            if (!$offline) {
                $model->borr_log = true;
            }else{
                $model->borr_log=false;
            }
            if ($model->save(false)) {

                $rol_user = new AuthAssignment();
                $rol_user->user_id = $model->id_usuario;
                $rol_user->item_name = 'Usuario';
                if ($rol_user->save()) {
                    if (!$offline) {
                        $id = urlencode($model->id_usuario);
                        $authKey = urlencode($model->getAuthKey());
                        $subject = "ExpressDealer: Hola, deseamos confirmar tu registro";
                        $body = '<h1>Haga click en el siguiente enlace para completar el registro</h1>';
                        $body .= '<a href="' . $_SERVER['HTTP_HOST'] . Yii::$app->homeUrl . '?r=usuario/confirm&id=' . $id . '&authKey=' . $authKey . '">Confirmar</a>';
                        try {
                            Yii::$app->mailer->compose()
                                ->setTo($model->correo)
                                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->params['title']])
                                ->setSubject($subject)
                                ->setHtmlBody($body)
                                ->send();
                            return $this->render('redirecting');
                        } catch (Exception $ex) {
                            $model->borr_log = false;
                            if ($model->update()) {
                                $log=new LoginForm();
                                $log->username=$model->nom_user;
                                $log->password=$pass;
                                if($log->login()) {
                                    return $this->redirect(['site/index']);
                                }else{
                                    return $this->redirect(['site/login']);
                                }
                            }
                        }
                        //echo 'Compruebe su correo para confirmar su cuenta, gracias...';
                        //return $this->redirect(['/site/login', 'id' => $model->id_usuario]);
                    } else {
                        $log=new LoginForm();
                        $log->username=$model->nom_user;
                        $log->password=$pass;
                        if($log->login()) {
                            return $this->redirect(['site/index']);
                        }else{
                            return $this->redirect(['site/login']);
                        }
                    }

                } else {
                    return $this->render('create', [
                        'model' => $model,
                        'admin' => false,
                    ]);
                }

            } else {
                return $this->render('create', [
                    'model' => $model,
                    'admin' => false,
                ]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'admin' => false,

        ]);

    }

    public function actionCreateAdmin()
    {
        $model = new Usuario();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->clave = Yii::$app->getSecurity()->generatePasswordHash($model->clave);
            $model->auth_key = $this->randKey('abcdefg123456789', 200);
            $offline = Yii::$app->params['isOffline'];
            if (!$offline) {
                $model->borr_log = true;
            }else{
                $model->borr_log=false;
            }
            if ($model->save(false)) {

                $rol_user = new AuthAssignment();
                $rol_user->user_id = $model->id_usuario;
                $rol_user->item_name = 'Administrador';
                if ($rol_user->save()) {
                    if (!$offline) {
                        $id = urlencode($model->id_usuario);
                        $authKey = urlencode($model->getAuthKey());
                        $subject = "ExpressDealer: Hola, deseamos confirmar tu registro";
                        $body = '<h1>Haga click en el siguiente enlace para completar el registro</h1>';
                        $body .= '<a href="' . $_SERVER['HTTP_HOST'] . Yii::$app->homeUrl . '?r=usuario/confirm&id=' . $id . '&authKey=' . $authKey . '">Confirmar</a>';
                        try {
                            Yii::$app->mailer->compose()
                                ->setTo($model->correo)
                                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->params['title']])
                                ->setSubject($subject)
                                ->setHtmlBody($body)
                                ->send();
                            return $this->render('redirecting');
                        } catch (Exception $ex) {
                            $model->borr_log = false;
                            if ($model->update()) {
                                return $this->redirect(['site/login']);
                            }
                        }
                        //echo 'Compruebe su correo para confirmar su cuenta, gracias...';
                        //return $this->redirect(['/site/login', 'id' => $model->id_usuario]);
                    } else {
                        $this->redirect(['site/index']);
                    }

                } else {
                    return $this->render('create', [
                        'model' => $model,
                        'admin' => true,
                    ]);
                }

            } else {
                return $this->render('create', [
                    'model' => $model,
                    'admin' => true,
                ]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'admin' => false,

        ]);

    }

    /**
     * Updates an existing Usuario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model1 = $this->findModel($id);
        $model = new ModifyUserModel();
        $model->nom_user = $model1->nom_user;
        $model->nombre = $model1->nombre;
        $model->apellido = $model1->apellido;
        $model->pais = $model1->pais;
        $model->correo = $model1->correo;
        if ($model->load(Yii::$app->request->post())) {
            $model1->nom_user = $model->nom_user;
            $model1->nombre = $model->nombre;
            $model1->apellido = $model->apellido;
            $model1->pais = $model->pais;
            $model1->correo = $model->correo;
            if (!empty($model->claveOld)) {
                if ($model1->validatePassword($model->claveOld)) {
                    if ($model->clave == $model->claverepeat && !empty($model->clave)) {
                        $model1->clave = Yii::$app->getSecurity()->generatePasswordHash($model->clave);;
                        $model1->claverepeat = $model->claverepeat;
                    }
                } else {
                    $model->addError('claveOld', 'La contraseña proporcionada no es valida');
                    return $this->render('modifyForm', [
                        'model' => $model,
                    ]);
                }
            }
            if(empty($model->claveOld)&&(!empty($model->clave)||!empty($model->claverepeat))){
                $model->addError('claveOld','Debe introducir su contraseña actual antes de modificarla');
                return $this->render('modifyForm', [
                    'model' => $model,
                ]);
            }
            $model1->save(false);
            return $this->redirect(['perfil', 'id_user' => $model1->id_usuario]);

        } else {
            return $this->render('modifyForm', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Usuario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the Usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPerfil()
    {
        return $this->render('perfil', ['id_user' => null]);
    }

    public function actionVisitProfile($id_user)
    {
        $id_user = Html::encode($id_user);
        return $this->render('perfil', ['id_user' => $id_user]);
    }

    public function actionDeshabilitar($id)
    {
        $time = new \DateTime('now');
        $time->modify('15 days');
        $time = $time->format('y-m-d H:i');

        $razon = 'Usted ha sido inhabilitado por mal uso de nuestros servicios, para mas informacion contactanos';
        $disable = new Deshabilitado();
        $id = Html::encode($id);
        $time = Html::encode($time);
        $razon = Html::encode($razon);
        $disable->usuario = $id;
        $disable->tiempo = $time;
        $disable->razon = $razon;

        if ($disable->save(false)) {
            if (!Yii::$app->params['isOffline']) {
                try {
                    Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo(Usuario::findOne(['id_usuario' => $id])->correo)
                        ->setSubject('ExpressDealer: Su cuenta ha sido deshabilitada')
                        ->setTextBody('El equipo de ExpressDealer le informa que su cuenta ha sido deshabilitada por mal uso de nuestros servicios')
                        ->send();
                } catch (Exception $e) {

                }
            }
            //  return json_encode(array('successfull' => true));
            return $this->actionIndex();
        }

//        } else {
//            return json_encode(array('successfull' => false));
//        }
    }

    public function actionHabilitar($id)
    {
        $id = Html::encode($id);
        Deshabilitado::deleteAll('usuario=' . $id);
        if (!Yii::$app->params['isOffline']) {
            try {
                Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo(Usuario::findOne(['id_usuario' => $id])->correo)
                    ->setSubject('ExpressDealer: Su cuenta ha sido rehabilitada')
                    ->setTextBody('El equipo de ExpressDealer le informa que su cuenta ha sido habilitada nuevamente')
                    ->send();
            } catch (Exception $e) {

            }
        }
        return $this->actionIndex();
    }

    public function actionEliminar($id)
    {
        Usuario::updateAll(['borr_log' => true], 'id_usuario=' . $id);
        try {
            if(!Yii::$app->params['isOffline']) {
                Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo(Usuario::findOne(['id_usuario' => $id])->correo)
                    ->setSubject('ExpressDealer: Expulsion del sitio')
                    ->setTextBody('El equipo de ExpressDealer le informa que usted ha sido terminantemente expulsado del sitio por repetido uso indebido de nuestro sitio')
                    ->send();
            }
        } catch (Exception $e) {

        }
        return $this->actionIndex();
    }

    private function randKey($str = '', $long = 0)
    {
        $key = null;
        $str = str_split($str);
        $start = 0;
        $limit = count($str) - 1;
        for ($i = 0; $i < $long; $i++) {
            $key .= $str[rand($start, $limit)];
        }
        return $key;
    }

    public function actionConfirm()
    {

        if (Yii::$app->request->get()) {


            $id = Html::encode($_GET['id']);
            $authKey = Html::decode($_GET['authKey']);
            if ((int)($id)) {
                $model = Usuario::findOne(['id_usuario' => $id, 'auth_key' => $authKey]);
                if ($model != null) {
                    // $model->updateAll(['borr_log' => false], 'id_usuario=' . $id);
                    $model->borr_log = false;
                    $model->save(false);
                    $this->redirect(['/site/login', 'id' => $model->id_usuario]);
                    //} else {
                    // $this->redirect(['/site/login']);
                    //}

                } else {
                    //  $this->redirect(['/site/login',]);

                }
            } else {
                // $this->redirect(['/site/login',]);
            }
        }
    }
}
