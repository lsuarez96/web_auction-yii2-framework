<?php

namespace app\controllers;

use app\models\Producto;
use app\models\Subasta;
use Yii;
use app\models\Subastaseguida;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubastaseguidaController implements the CRUD actions for Subastaseguida model.
 */
class SubastaseguidaController extends Controller
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
        ];
    }

    /**
     * Lists all SubastaSeguida models.
     * @return mixed
     */
//    public function actionIndex()
//    {
//        $dataProvider = new ActiveDataProvider([
//            'query' => Subastaseguida::find(),
//        ]);
//
//        return $this->render('index', [
//            'dataProvider' => $dataProvider,
//        ]);
//    }

    /**
     * Displays a single SubastaSeguida model.
     * @param integer $id
     * @return mixed
     */
//    public function actionView($id_user)
//    {
//        return $this->render('view', [
//            'model' => Subastaseguida::getFollowedByUser($id_user),
//        ]);
//    }

    /**
     * Creates a new SubastaSeguida model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new Subastaseguida();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id_subasta_seguida]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Updates an existing SubastaSeguida model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id_subasta_seguida]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Deletes an existing SubastaSeguida model.
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
     * Finds the SubastaSeguida model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubastaSeguida the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subastaseguida::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionFollowProduct($id){
//        $id=Yii::$app->security->decryptByPassword($id,Yii::$app->params['salt']);
//        var_dump($id);
        //die();
        $auction=Subasta::findOne(['id_producto'=>$id]);
        if($auction!=null) {
            if (!Yii::$app->user->isGuest) {
                $follow = Subastaseguida::findOne(['subasta_id' => $auction->id_subasta, 'usuario' => Yii::$app->user->id]);
                if ($follow == null) {
                    $follow = new Subastaseguida();
                    $follow->usuario = Yii::$app->user->id;
                    $follow->subasta_id = $auction->id_subasta;
                    $follow->save(false);
                    return $this->redirect(['producto/view', 'id' => $id]);
                } else {
                    $follow->delete();
                }
            }
            return $this->redirect(['producto/view', 'id' => $id]);
        }
        return $this->goHome();
    }
}
