<?php

namespace app\controllers;

use app\models\OrdersGoods;
use app\models\OrdersProducts;
use app\models\Places;
use app\models\PlacesGoods;
use app\models\ProductsPlaces;
use app\models\UserIdentity;
use app\models\Users;
use Yii;
use app\models\Orders;
use app\models\Orders_Search;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends AppController
{

    public $action_lists = ['index', 'view', 'create', 'delete', 'delete-products', 'delete-goods'];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

        $array = parent::behaviors();
        $array['access']['rules'][0]['allow'] = true;

        return $array;
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $queryParams = Yii::$app->request->queryParams;
        if ($this->isUser()) {
            $queryParams['Orders_Search']['places_id'] = Yii::$app->user->identity->places_id;
            $queryParams['Orders_Search']['users_id'] = Yii::$app->user->identity->id;
        }

        $searchModel = new Orders_Search();
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Users' => Users::find()->all(),
            'Places' => Places::find()->all(),
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);

        if ($this->isUser()) {
            if (!$this->isPlace($model->places_id)) {
                return $this->redirect(['/orders']);
            }
        }

        $OrdersGoods = new OrdersGoods();
        $OrdersProducts = new OrdersProducts();

        if ($OrdersGoods->load(Yii::$app->request->post())) {

            $OrdersGoods->orders_id = $id;
            $OrdersGoods->price_out = $OrdersGoods->goods->price_out;
            $model->total = $model->total + ($OrdersGoods->goods->price_out * $OrdersGoods->count);

            $PlacesGoods = PlacesGoods::findOne(['places_id' => $model->places_id, 'goods_id' => $OrdersGoods->goods_id]);

            if ($PlacesGoods->count >= $OrdersGoods->count) {

                $PlacesGoods->count = $PlacesGoods->count - $OrdersGoods->count;

                if ($model->save() && $OrdersGoods->save() && $PlacesGoods->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        if ($OrdersProducts->load(Yii::$app->request->post())) {

            $OrdersProducts->orders_id = $id;
            $OrdersProducts->price_out = $OrdersProducts->products->price_out;
            $model->total = $model->total + ($OrdersProducts->products->price_out * $OrdersProducts->count);

            $canBuy = true;
            $PlacesGoodsModal = [];
            $chargeOff = [];
            foreach ($OrdersProducts->products->productsGoods as $good) {

                $PlacesGoods = PlacesGoods::findOne(['places_id' => $model->places_id, 'goods_id' => $good->goods_id]);

                if ($PlacesGoods->count >= ($good->count * $OrdersProducts->count)) {

                    $PlacesGoods->count = $PlacesGoods->count - ($good->count * $OrdersProducts->count);
                    $PlacesGoodsModal[] = $PlacesGoods;

                    $chargeOff[] = [
                        'goods_id' => $good->goods_id,
                        'places_id' => $model->places_id,
                        'count' => ($good->count * $OrdersProducts->count),
                    ];

                } else {
                    $canBuy = false;
                    break;
                }
            }

            if ($canBuy) {

                $OrdersProducts->charge_off = serialize($chargeOff);

                foreach ($PlacesGoodsModal as $item) {
                    $item->save();
                }

                if ($model->save() && $OrdersProducts->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }

            // Списание с остатка
            /*
            if ($model->save() && $OrdersProducts->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            */
        }

        return $this->render('view', [
            'model' => $model,
            'OrdersGoodsModel' => $OrdersGoods,
            'OrdersProductsModel' => $OrdersProducts,
            'PlacesGoods' => PlacesGoods::find()->where(['>', 'count', 0])->andWhere(['places_id' => $model->places_id])->all(),
            'ProductsPlaces' => ProductsPlaces::find()->where(['places_id' => $model->places_id])->all(),
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        if ($this->isUser()) {

            $model->places_id = Yii::$app->user->identity->places_id;
            $model->users_id = Yii::$app->user->identity->id;
            $model->date_add = date('Y-m-d H:i:s');

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->date_add = date('Y-m-d H:i:s');
            $model->users_id = Yii::$app->user->identity->getId();
            $model->total = 0;

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'Places' => Places::find()->all(),
        ]);
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteProducts($id)
    {
        $model = $this->findModelOrdersProducts($id);
        $modelOrders = $this->findModel($model->orders_id);
        $modelOrders->total = $modelOrders->total - ($model->price_out * $model->count);

        if ($modelOrders->save()) {
            $charge_off = unserialize($model->charge_off);
            if(!empty($charge_off)){
                foreach ($charge_off as $item) {
                    $PlacesGoods = PlacesGoods::findOne(['places_id' => $item['places_id'], 'goods_id' => $item['goods_id']]);
                    $PlacesGoods->count = $PlacesGoods->count + $item['count'];
                    $PlacesGoods->save();
                }
            }
        }

        $model->delete();

        return $this->redirect(['view', 'id' => $modelOrders->id]);
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteGoods($id)
    {
        $model = $this->findModelOrdersGoods($id);
        $modelOrders = $this->findModel($model->orders_id);
        $modelOrders->total = $modelOrders->total - ($model->price_out * $model->count);

        $PlacesGoods = PlacesGoods::findOne(['places_id' => $modelOrders->places_id, 'goods_id' => $model->goods_id]);
        $PlacesGoods->count = $PlacesGoods->count + $model->count;

        if ($modelOrders->save() && $PlacesGoods->save()) {
            $model->delete();
        }

        return $this->redirect(['view', 'id' => $modelOrders->id]);
    }

    /**
     * Delete Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrdersProducts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelOrdersProducts($id)
    {
        if (($model = OrdersProducts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrdersGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelOrdersGoods($id)
    {
        if (($model = OrdersGoods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
