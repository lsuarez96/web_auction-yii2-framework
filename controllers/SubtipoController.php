<?php

namespace app\controllers;

use app\models\Tipo;
use Yii;
use app\models\Subtipo;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubtipoController implements the CRUD actions for Subtipo model.
 */
class SubtipoController extends Controller
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
     * Lists all Subtipo models.
     * @return mixed
     */
//    public function actionIndex()
//    {
//        $dataProvider = new ActiveDataProvider([
//            'query' => Subtipo::find(),
//        ]);
//
//        return $this->render('index', [
//            'dataProvider' => $dataProvider,
//        ]);
//    }

    /**
     * Displays a single Subtipo model.
     * @param integer $id_sub_tipo
     * @param integer $id_tipo
     * @return mixed
     */
//    public function actionView($id_sub_tipo, $id_tipo)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id_sub_tipo, $id_tipo),
//        ]);
//    }

    /**
     * Creates a new Subtipo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new Subtipo();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id_sub_tipo' => $model->id_sub_tipo, 'id_tipo' => $model->id_tipo]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Updates an existing Subtipo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_sub_tipo
     * @param integer $id_tipo
     * @return mixed
     */
//    public function actionUpdate($id_sub_tipo, $id_tipo)
//    {
//        $model = $this->findModel($id_sub_tipo, $id_tipo);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id_sub_tipo' => $model->id_sub_tipo, 'id_tipo' => $model->id_tipo]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Deletes an existing Subtipo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_sub_tipo
     * @param integer $id_tipo
     * @return mixed
     */
//    public function actionDelete($id_sub_tipo, $id_tipo)
//    {
//        $this->findModel($id_sub_tipo, $id_tipo)->delete();
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the Subtipo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_sub_tipo
     * @param integer $id_tipo
     * @return Subtipo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_sub_tipo, $id_tipo)
    {
        if (($model = Subtipo::findOne(['id_sub_tipo' => $id_sub_tipo, 'id_tipo' => $id_tipo])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetSubtypes($type_id){
            $sub_types=Subtipo::find()->where(['id_tipo'=>$type_id])->all();
           foreach($sub_types as $rs){
               echo '<option value="'.$rs['id_sub_tipo'].'">'.$rs['sub_tipo'].'</option>';
           }

    }

    public function actionGetSubtypesForShopNavBar(){
        $types=Tipo::find()->all();
        echo '<div class="row" style="text-align: left;">';
        $size=round(12/count($types))-1;
        if($size==0)$size=1;
        foreach ($types as $typeItem){
            $subtypes=Subtipo::find()->where(['id_tipo'=>$typeItem->id_tipo])->all();
            echo '<div class="col1" style="margin-top: 10px;"><div class="h_nav"><h4>'.$typeItem->nom_tipo.'</h4>';
            echo '<ul>';
            foreach ($subtypes as $subtype) {
                echo '<li><a class="subtipo_element" tipo="'.$typeItem->id_tipo.'" subtipo="'.$subtype->id_sub_tipo.'" href="index.php?r=producto/get-products-by-subtype&type='.$subtype->id_sub_tipo.'&page=1" >'.$subtype->sub_tipo.'</a></li>';
            }
            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
}
