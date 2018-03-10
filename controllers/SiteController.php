<?php

namespace app\controllers;

use app\models\Pais;
use app\models\Usuario;


use mPDF;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'pdf'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    ['allow' => true, //Yii::$app->user->can('user'),
                        'actions' => ['pdf'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rules, $action) {
                            $valid_roles = ['Administrador'];
                            return true;//Usuario::roleInArray($valid_roles) && Usuario::isActive();
                        }
                    ],
                ],
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],


        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            if (Usuario::roleInArray(['Administrador'])) {
                return $this->render('admin');
            }
        }
        return $this->render('index');


    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if (!Yii::$app->user->isGuest) {
            $model->email = Usuario::findOne(['id_usuario' => Yii::$app->user->id])->correo;
            $model->name = Usuario::findOne(['id_usuario' => Yii::$app->user->id])->nom_user;
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->contact(Yii::$app->params['adminEmail'])) {              
                Yii::$app->session->setFlash('contactFormSubmitted');
            }

            return $this->refresh();
        }else{
           // var_dump($model);
            //die();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionPdf()
    {
        $mpdf = new \mPDF('win-1252', 'LETTER', '', '', 5, 15, 5, 12, 5, 7);

        $mpdf->WriteHTML($this->renderPartial('exitosos'));
        $mpdf->Output();
    }


}
