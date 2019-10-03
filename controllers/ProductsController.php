<?php

namespace app\controllers;

use app\models\Goods;
use app\models\Places;
use app\models\ProductsGoods;
use app\models\ProductsPlaces;
use Yii;
use app\models\Products;
use app\models\Products_Search;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends AppController
{

    public $action_lists = ['index', 'view', 'create', 'update', 'delete-goods', 'delete-places', 'delete'];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

        $array = parent::behaviors();
        if ($this->isAdmin()) {
            $array['access']['rules'][0]['allow'] = true;
        }

        return $array;
    }

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Products_Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $ProductsGoods = new ProductsGoods();
        $ProductsPlaces = new ProductsPlaces();

        if ($ProductsGoods->load(Yii::$app->request->post())) {
            $ProductsGoods->products_id = $id;
            if ($ProductsGoods->save()) {
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        if ($ProductsPlaces->load(Yii::$app->request->post())) {
            $ProductsPlaces->products_id = $id;
            if ($ProductsPlaces->save()) {
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('view', [
            'model' => $model,
            'Goods' => Goods::find()->where(['type' => 'ingredient'])->asArray()->all(),
            'Places' => Places::find()->asArray()->all(),
            'ProductsGoods' => $ProductsGoods,
            'ProductsPlaces' => $ProductsPlaces
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Products();

        if ($model->load(Yii::$app->request->post())) {

            $UploadedFile = UploadedFile::getInstance($model, 'image');
            $model->img = $UploadedFile;
            if($UploadedFile){
                $model->image = $UploadedFile->name;
                $model->upload();
            }

            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $UploadedFile = UploadedFile::getInstance($model, 'image');
            $model->img = $UploadedFile;
            if($UploadedFile){
                $model->image = $UploadedFile->name;
                $model->upload();
            }

            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DeleteGoods model.
     * If deletion is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $products_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteGoods($id, $products_id)
    {
        $this->findModelGoods($id)->delete();

        return $this->redirect(['view', 'id' => $products_id]);
    }

    /**
     * Deletes an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $places_id
     * @param $products_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeletePlaces($places_id, $products_id)
    {
        $this->findModelPlaces($places_id, $products_id)->delete();
        return $this->redirect(['view', 'id' => $products_id]);
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        foreach($this->findModelGoodsAll($id) as $item){
            $item->delete();
        }
        foreach($this->findModelPlacesAll($id) as $item){
            $item->delete();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductsGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelGoods($id)
    {
        if (($model = ProductsGoods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $products_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelGoodsAll($products_id)
    {
        if (($model = ProductsGoods::find()->andFilterWhere(['products_id' => $products_id])->all()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the ProductsPlaces model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $places_id
     * @param integer $products_id
     * @return ProductsPlaces the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelPlaces($places_id, $products_id)
    {
        if (($model = ProductsPlaces::findOne(['places_id' => $places_id, 'products_id' => $products_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the ProductsPlaces model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $products_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelPlacesAll($products_id)
    {
        if (($model = ProductsPlaces::find()->andFilterWhere(['products_id' => $products_id])->all()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
