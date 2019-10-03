<?php

namespace app\controllers;

use app\models\Goods;
use app\models\InvoicesGoodsStocks;
use app\models\Stocks;
use app\models\StocksGoods;
use app\models\Users;
use Yii;
use app\models\InvoicesStocks;
use app\models\InvoicesStocks_Search;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvoicesStocksController implements the CRUD actions for InvoicesStocks model.
 */
class InvoicesStocksController extends AppController
{

    public $action_lists = ['index', 'view', 'create', 'update', 'delete', 'delete-goods'];

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
     * Lists all InvoicesStocks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoicesStocks_Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Users' => Users::find()->asArray()->all(),
            'Stocks' => Stocks::find()->asArray()->all()
        ]);
    }

    /**
     * Displays a single InvoicesStocks model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);

        $InvoicesGoodsStocks = new InvoicesGoodsStocks();

        if ($InvoicesGoodsStocks->load(Yii::$app->request->post())) {

            $StocksGoods = StocksGoods::findOne(['stocks_id' => $model->stocks_id, 'goods_id' => $InvoicesGoodsStocks->goods_id]);
            if (!$StocksGoods) {
                $StocksGoods = new StocksGoods();
            }

            $StocksGoods->count = ($StocksGoods->count) ? $StocksGoods->count + $InvoicesGoodsStocks->count : $InvoicesGoodsStocks->count;
            $StocksGoods->stocks_id = $model->stocks_id;
            $StocksGoods->goods_id = $InvoicesGoodsStocks->goods_id;

            $InvoicesGoodsStocks->invoices_stocks_id = $id;

            $InvoicesGoodsStocks->price_in = $InvoicesGoodsStocks->price_in / $InvoicesGoodsStocks->count;
            $StocksGoods->price_in = $InvoicesGoodsStocks->price_in;

            if ($StocksGoods->save() && $InvoicesGoodsStocks->save()) {
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('view', [
            'model' => $model,
            'InvoicesGoodsStocks' => $InvoicesGoodsStocks,
            'Goods' => Goods::find()->asArray()->all(),
        ]);
    }

    /**
     * Creates a new InvoicesStocks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InvoicesStocks();

        if ($model->load(Yii::$app->request->post())) {

            $model->users_id = Yii::$app->user->identity->id;
            $model->date_add = date('Y-m-d H:i:s');

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'Stocks' => Stocks::find()->asArray()->all(),
        ]);
    }

    /**
     * Updates an existing InvoicesStocks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->users_id = Yii::$app->user->identity->id;

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes InvoicesGoodsStocks model.
     * Deletes InvoicesStocks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @redirect mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $redirect = ['view', 'id' => $id];
        $InvoicesGoodsStocks = InvoicesGoodsStocks::find()->where(['invoices_stocks_id' => $id])->all();

        foreach ($InvoicesGoodsStocks as $goods) {
            $StocksGoods = StocksGoods::findOne(['stocks_id' => $model->stocks_id, 'goods_id' => $goods->goods_id]);
            if ($StocksGoods->count >= $goods->count) {
                $StocksGoods->count = $StocksGoods->count - $goods->count;
                if ($StocksGoods->save()) {
                    $goods->delete();
                }
            }
        }

        $InvoicesGoodsStocks = InvoicesGoodsStocks::find()->where(['invoices_stocks_id' => $id])->all();
        if ($InvoicesGoodsStocks == null) {
            $model->delete();
            $redirect = ['index'];
        }

        return $this->redirect($redirect);
    }

    /**
     * Delete InvoicesGoodsStocks model.
     * @param integer $id
     * @param integer $goods_id
     * @param integer $stocks_id
     * @param integer $invoices_stocks_id
     * @redirect mixed
     */
    public function actionDeleteGoods($id, $goods_id, $stocks_id, $invoices_stocks_id)
    {

        $StocksGoods = StocksGoods::findOne(['stocks_id' => $stocks_id, 'goods_id' => $goods_id]);
        $InvoicesGoodsStocks = InvoicesGoodsStocks::findOne(['id' => $id, 'invoices_stocks_id' => $invoices_stocks_id, 'goods_id' => $goods_id]);

        if ($InvoicesGoodsStocks && $StocksGoods) {

            if ($StocksGoods->count >= $InvoicesGoodsStocks->count) {

                $StocksGoods->count = $StocksGoods->count - $InvoicesGoodsStocks->count;
                if ($StocksGoods->save()) {
                    $InvoicesGoodsStocks->delete();
                }
            }
        }

        return $this->redirect(['view', 'id' => $invoices_stocks_id]);
    }

    /**
     * Finds the InvoicesStocks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InvoicesStocks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvoicesStocks::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
