<?php

namespace app\controllers;

use Yii;
use app\models\Goods;
use app\models\Goods_Search;
use yii\web\NotFoundHttpException;
use app\models\InvoicesGoods;
use yii\web\UploadedFile;

/**
 * GoodsController implements the CRUD actions for Goods model.
 */
class GoodsController extends AppController
{
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
     * Lists all Goods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Goods_Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Goods model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'InvoicesGoods' => InvoicesGoods::find()->andFilterWhere([ 'goods_id' => $id ])->all(),
        ]);
    }

    /**
     * Creates a new Goods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Goods();

        if ($model->load(Yii::$app->request->post())) {
            $model->price_in = 0;

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
     * Updates an existing Goods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $image = $model->image;
        if ($model->load(Yii::$app->request->post())) {

            $model->image = $image;
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
     * Deletes an existing Goods model.
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
     * Finds the Goods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
