<?php

namespace app\controllers;

use app\models\Usuario;
use Yii;
use app\models\Comentario;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ComentarioController implements the CRUD actions for Comentario model.
 */
class ComentarioController extends Controller
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
                'only' => ['post-coment'],
                'rules' => [
                    ['allow' => true, //Yii::$app->user->can('user'),
                        'actions' => ['post-coment'],
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
     * Lists all Comentario models.
     * @return mixed
     */
//    public function actionIndex()
//    {
//        $dataProvider = new ActiveDataProvider([
//            'query' => Comentario::find(),
//        ]);
//
//        return $this->render('index', [
//            'dataProvider' => $dataProvider,
//        ]);
//    }
//
//    /**
//     * Displays a single Comentario model.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }
//
//    /**
//     * Creates a new Comentario model.
//     * If creation is successful, the browser will be redirected to the 'view' page.
//     * @return mixed
//     */
//    public function actionCreate()
//    {
//        $model = new Comentario();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id_comentario]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }
//
//    /**
//     * Updates an existing Comentario model.
//     * If update is successful, the browser will be redirected to the 'view' page.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id_comentario]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
//    }
//
//    /**
//     * Deletes an existing Comentario model.
//     * If deletion is successful, the browser will be redirected to the 'index' page.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the Comentario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comentario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comentario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionPostComent($product,$coment){

        $modelo=new Comentario();
        $modelo->id_usuario=Yii::$app->user->id;
        $modelo->id_producto=$product;
        $modelo->comentario=$coment;
        if($modelo->save(false)){
            $result=array('usuario'=>Usuario::findOne(['id_usuario'=>$modelo->id_usuario])->nom_user,'coment'=>$coment);
            echo json_encode($result);

        }else{
            echo json_encode(array('error_message'=>'true'));
        }
    }
}
