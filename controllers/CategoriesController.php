<?php

namespace app\controllers;

use app\models\CategoriesPlaces;
use app\models\CategoriesPlacesGoods;
use app\models\CategoriesPlacesProducts;
use app\models\Goods;
use app\models\Places;
use app\models\Products;
use Yii;
use app\models\Categories;
use app\models\CategoriesSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends AppController
{

    public $action_lists = ['index', 'view', 'view-places', 'create', 'update', 'delete-places', 'delete-goods', 'delete-products', 'delete'];

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
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Categories model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $CategoriesPlaces = new CategoriesPlaces();
        $Places = Places::find()->asArray()->all();

        if ($CategoriesPlaces->load(Yii::$app->request->post())) {

            $CategoriesPlaces->categories_id = $id;

            if ($CategoriesPlaces->save()) {
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'CategoriesPlaces' => $CategoriesPlaces,
            'Places' => $Places,
        ]);
    }

    /**
     * Displays a single CategoriesPlaces model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewPlaces($id)
    {
        $CategoriesPlacesGoods = new CategoriesPlacesGoods();
        $CategoriesPlacesProducts = new CategoriesPlacesProducts();

        if ($CategoriesPlacesGoods->load(Yii::$app->request->post())) {

            $CategoriesPlacesGoods->categories_places_id = $id;

            if ($CategoriesPlacesGoods->save()) {
                return $this->redirect(['view-places', 'id' => $id]);
            }
        }

        if ($CategoriesPlacesProducts->load(Yii::$app->request->post())) {

            $CategoriesPlacesProducts->categories_places_id = $id;

            if ($CategoriesPlacesProducts->save()) {
                return $this->redirect(['view-places', 'id' => $id]);
            }
        }

        return $this->render('view-places', [
            'model' => $this->findModelCategoriesPlaces($id),
            'CategoriesPlacesGoods' => $CategoriesPlacesGoods,
            'CategoriesPlacesProducts' => $CategoriesPlacesProducts,
            'Goods' => Goods::find()->where(['type' => 'goods'])->all(),
            'Products' => Products::find()->all(),
        ]);
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Categories();

        if ($model->load(Yii::$app->request->post())) {

            $model->date_add = date('Y-m-d H:i:s');
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeletePlaces($id)
    {

        $model = $this->findModelCategoriesPlaces($id);
        $model->delete();

        return $this->redirect(['view', 'id' => $model->categories_id]);
    }

    /**
     * Deletes an existing CategoriesPlacesGoods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteGoods($id)
    {

        $model = $this->findModelCategoriesPlacesGoods($id);
        $model->delete();

        return $this->redirect(['view-places', 'id' => $model->categories_places_id]);
    }

    /**
     * Deletes an existing CategoriesPlacesProducts( model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteProducts($id)
    {

        $model = $this->findModelCategoriesPlacesProducts($id);
        $model->delete();

        return $this->redirect(['view-places', 'id' => $model->categories_places_id]);
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the CategoriesPlaces model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CategoriesPlaces the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelCategoriesPlaces($id)
    {
        if (($model = CategoriesPlaces::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the CategoriesPlacesGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CategoriesPlacesGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelCategoriesPlacesGoods($id)
    {
        if (($model = CategoriesPlacesGoods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the CategoriesPlacesProducts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CategoriesPlacesProducts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelCategoriesPlacesProducts($id)
    {
        if (($model = CategoriesPlacesProducts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
