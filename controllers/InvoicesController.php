<?php

namespace app\controllers;

use app\models\Goods;
use app\models\InvoicesGoods;
use app\models\Places;
use app\models\PlacesGoods;
use app\models\Stocks;
use app\models\StocksGoods;
use app\models\Users;
use Yii;
use app\models\Invoices;
use app\models\Invoices_Search;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * InvoicesController implements the CRUD actions for Invoices model.
 */
class InvoicesController extends AppController
{

    public $action_lists = ['index', 'view', 'create', 'update', 'delete', 'delete-goods'];

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
     * Lists all Invoices models.
     * @return mixed
     */
    public function actionIndex()
    {

        $queryParams = Yii::$app->request->queryParams;
        if ($this->isUser()) {
            $queryParams['Invoices_Search']['places_id'] = Yii::$app->user->identity->places_id;
        }

        $searchModel = new Invoices_Search();
        $dataProvider = $searchModel->search($queryParams);

        $array = [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Users' => Users::find()->asArray()->all(),
            'Stocks' => Stocks::find()->asArray()->all(),
            'Places' => Places::find()->asArray()->all(),
        ];

        return $this->render('index', $array);
    }

    /**
     * Displays a single Invoices model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);

        if ($this->isUser()) {
            if (!$this->isPlace($model->places_id)) {
                return $this->redirect(['/invoices']);
            }
        }

        $InvoicesGoods = new InvoicesGoods();

        if ($InvoicesGoods->load(Yii::$app->request->post())) {

            $PlacesGoods = PlacesGoods::findOne(['places_id' => $model->places_id, 'goods_id' => $InvoicesGoods->goods_id]);
            if ($PlacesGoods == null) {
                $PlacesGoods = new PlacesGoods();
            }

            // Если списание должно быть со склада
            if ($model->stocks_id > 0) {

                // Получаем товар на складе
                $StocksGoods = StocksGoods::findOne(['stocks_id' => $model->stocks_id, 'goods_id' => $InvoicesGoods->goods_id]);
                if ($StocksGoods == null) {
                    return $this->redirect(['view', 'id' => $id]);
                }

                // Если на складе меньше товара чем в инвойсе
                if ($StocksGoods->count < $InvoicesGoods->count) {
                    return $this->redirect(['view', 'id' => $id]);
                }

                // Обновляем остаток на складе.
                $StocksGoods->count = $StocksGoods->count - $InvoicesGoods->count;
                if (!$StocksGoods->save()) {
                    return $this->redirect(['view', 'id' => $id]);
                }
            }

            $PlacesGoods->count = ($PlacesGoods->count) ? $PlacesGoods->count + $InvoicesGoods->count : $InvoicesGoods->count;
            $PlacesGoods->places_id = $model->places_id;
            $PlacesGoods->goods_id = $InvoicesGoods->goods_id;

            $InvoicesGoods->invoices_id = $id;

            // Переводим сумму за всё в сумму за 1 ед.
            $InvoicesGoods->price_in = $InvoicesGoods->price_in / $InvoicesGoods->count;

            // Цена входящего товара считается по последнему входящему инвойсу.
            $GoodsModel = Goods::findOne($PlacesGoods->goods_id);
            $GoodsModel->price_in = $InvoicesGoods->price_in;

            if ($GoodsModel->save() && $InvoicesGoods->save() && $PlacesGoods->save()) {
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        $array = [
            'model' => $model,
            'InvoicesGoods' => $InvoicesGoods,
            'Goods' => Goods::find()->asArray()->all(),
            'Stocks' => Stocks::find()->asArray()->all(),
        ];

        return $this->render('view', $array);
    }

    /**
     * Creates a new Invoices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Invoices();

        if ($model->load(Yii::$app->request->post())) {

            $model->users_id = Yii::$app->user->identity->id;
            $model->date_add = date('Y-m-d H:i:s');

            if ($this->isUser()) {
                $model->places_id = Yii::$app->user->identity->places_id;
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'Stocks' => Stocks::find()->asArray()->all(),
            'Places' => Places::find()->asArray()->all(),
        ]);
    }

    /**
     * Updates an existing Invoices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);

        if ($this->isUser()) {
            if (!$this->isPlace($model->places_id)) {
                return $this->redirect(['/invoices']);
            }
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->users_id = Yii::$app->user->identity->id;

            if ($this->isUser()) {
                $model->places_id = Yii::$app->user->identity->places_id;
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'Users' => Users::find()->asArray()->all(),
            'Places' => Places::find()->asArray()->all(),
            'Stocks' => Stocks::find()->asArray()->all(),
        ]);
    }

    /**
     * Delete Invoices model.
     * @param integer $id
     * @redirect mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        /* Проверка может ли пользователь удалять этот Invoice */
        if ($this->isUser()) {
            if ($model->places_id !== Yii::$app->user->identity->places_id) {
                return $this->redirect(['index']);
            }
        }

        $redirect = ['view', 'id' => $id];
        $InvoicesGoods = InvoicesGoods::find()->where(['invoices_id' => $id])->all();

        foreach ($InvoicesGoods as $goods) {
            $PlacesGoods = PlacesGoods::findOne(['places_id' => $model->places_id, 'goods_id' => $goods->goods_id]);
            if ($PlacesGoods->count >= $goods->count) {
                $PlacesGoods->count = $PlacesGoods->count - $goods->count;
                if ($PlacesGoods->save()) {
                    $goods->delete();
                }
            }
        }

        $InvoicesGoods = InvoicesGoods::find()->where(['invoices_id' => $id])->all();
        if ($InvoicesGoods == null) {
            $model->delete();
            $redirect = ['index'];
        }

        return $this->redirect($redirect);
    }

    /**
     * Delete InvoicesGoods model.
     * @param integer $id
     * @param integer $invoices_goods
     * @redirect mixed
     */
    public function actionDeleteGoods($id)
    {

        $model = $this->findModelGoods($id);

        $PlacesGoods = PlacesGoods::findOne(['places_id' => $model->invoices->places_id, 'goods_id' => $model->goods_id]);
        if ($model->invoices->stocks_id > 0) {

            $StocksGoods = StocksGoods::findOne(['stocks_id' => $model->invoices->stocks_id, 'goods_id' => $model->goods_id]);
            $StocksGoods->count = $StocksGoods->count + $model->count;

            if (!$StocksGoods->save()) {
                return $this->redirect(['view', 'id' => $model->invoices_id]);
            }
        }

        if ($model && $PlacesGoods) {
            if ($PlacesGoods->count >= $model->count) {
                $PlacesGoods->count = $PlacesGoods->count - $model->count;
                if ($PlacesGoods->save()) {
                    $model->delete();
                }
            }
        }

        return $this->redirect(['view', 'id' => $model->invoices_id]);
    }

    /**
     * Finds the Invoices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoices::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the InvoicesGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InvoicesGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelGoods($id)
    {
        if (($model = InvoicesGoods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
