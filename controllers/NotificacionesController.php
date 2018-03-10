<?php

namespace app\controllers;

use app\models\Usuario;
use Yii;
use app\models\Notificaciones;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NotificacionesController implements the CRUD actions for Notificaciones model.
 */
class NotificacionesController extends Controller
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
                'only' => ['notificaciones-scroll','index'],
                'rules' => [
                    ['allow' => true, //Yii::$app->user->can('user'),
                        'actions' => ['notificaciones-scroll','index'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rules, $action) {
                            $valid_roles = ['Usuario'];
                            return Usuario::roleInArray($valid_roles) && Usuario::isActive();
                        }
                    ],

                ],
            ],
        ];
    }

    /**
     * Lists all Notificaciones models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $dataProvider = new ActiveDataProvider([
//            'query' => Notificaciones::find(),
//        ]);
        Yii::$app->user->isGuest == false ? $resultset = Notificaciones::find()->where(['usuario_id' => Yii::$app->user->id])->orderBy(['id_notificaciones' => SORT_DESC])->limit(15)->all() : $resultset = false;
        return $this->render('index',
            ['resultset' => $resultset]
//            'dataProvider' => $dataProvider,
        );
    }

    /**
     * Displays a single Notificaciones model.
     * @param integer $id
     * @return mixed
     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

    /**
     * Creates a new Notificaciones model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new Notificaciones();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id_notificaciones]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Updates an existing Notificaciones model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id_notificaciones]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Deletes an existing Notificaciones model.
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
     * Finds the Notificaciones model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notificaciones the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notificaciones::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionNotificacionesScroll($page)
    {
        $sql = 'SELECT notificaciones.* FROM notificaciones   WHERE  notificaciones.usuario_id=' . Yii::$app->user->id . ' ORDER BY notificaciones.id_notificaciones DESC';
        if ($page == 0) {
            $page = 1;
        }
        $initial = $page == 1;
        $perPage = 10;
        $start = ($page - 1) * $perPage;
        if ($start < 0) $start = 0;
        $query = $sql . " limit " . $start . "," . $perPage;
        $resultset = Notificaciones::findBySql($query)->all();

        if (!empty($resultset) && !$initial) {
            foreach ($resultset as $item) {
                if ($item->nuevo) {
                    echo '<tr class="alert-info pagina" pag="' . $page . '"><td><span class="badge">new</span>&nbsp;' . $item->nota . '</td></tr>';
                } else {
                    echo '<tr class="pagina" pag="' . $page . '"><td>' . $item->nota . '</td></tr>';
                }
                \app\models\Notificaciones::updateAll(['nuevo' => false], ['id_notificaciones' => $item->id_notificaciones]);
            }
        }

    }
}
