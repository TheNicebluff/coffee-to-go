<?php

namespace app\controllers;

use app\models\Categories;
use app\models\CategoriesPlaces;
use app\models\Goods;
use app\models\Orders;
use app\models\OrdersGoods;
use app\models\OrdersProducts;
use app\models\PlacesGoods;
use app\models\Products;
use app\models\ResetPasswordForm;
use Yii;
use yii\base\ActionEvent;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\Cookie;
use app\models\LoginForm;
use app\models\PasswordResetRequestForm;

class SiteController extends AppController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        $event = new ActionEvent($action);
        $this->trigger(self::EVENT_BEFORE_ACTION, $event);
        return $event->isValid;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);
        }

        $index = 'index';
        $CategoriesPlaces = [];
        if ($this->isUser()) {
            $index = 'index-user';
            $CategoriesPlaces = CategoriesPlaces::find()->andFilterWhere(['places_id' => Yii::$app->user->identity->places_id])->all();
        }

        return $this->render($index, [
            'CategoriesPlaces' => $CategoriesPlaces
        ]);
    }

    /**
     * Add new order
     * @return Response|string
     */
    public function actionAddOrder()
    {

        if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);
        }

        $model = new Orders();
        $model->places_id = Yii::$app->user->identity->places_id;
        $model->users_id = Yii::$app->user->identity->id;
        $model->date_add = date('Y-m-d H:i:s');

        if ($model->save()) {
            setcookie("order_id", $model->id, time() + 3600, '/');
        }

        return $this->goHome();
    }

    /**
     * Add product in order
     * @param int $products_id
     * @return Response|string
     */
    public function actionAddProductsOrder($products_id)
    {

        if (!isset($_COOKIE['order_id'])|| empty($_COOKIE['order_id']))
            return $this->goHome();

        $id = (int)$_COOKIE['order_id'];

        $Orders = Orders::findOne($id);
        $Products = Products::findOne($products_id);
        $OrdersProducts = new OrdersProducts();

        $OrdersProducts->orders_id = $Orders->id;
        $OrdersProducts->price_out = $Products->price_out;
        $OrdersProducts->count = 1;
        $OrdersProducts->products_id = $products_id;

        $OrdersProducts->orders_id = $id;
        $OrdersProducts->price_out = $Products->price_out;
        $Orders->total = $Orders->total + ($Products->price_out * 1);

        $canBuy = true;
        $PlacesGoodsModal = [];
        $chargeOff = [];
        foreach ($Products->productsGoods as $good) {

            $PlacesGoods = PlacesGoods::findOne(['places_id' => $Orders->places_id, 'goods_id' => $good->goods_id]);

            if ($PlacesGoods->count >= $good->count) {

                $PlacesGoods->count = $PlacesGoods->count - $good->count;
                $PlacesGoodsModal[] = $PlacesGoods;

                $chargeOff[] = [
                    'goods_id' => $good->goods_id,
                    'places_id' => $Orders->places_id,
                    'count' => $good->count,
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

            if ($Orders->save() && $OrdersProducts->save()) {
            }
        }

        return $this->goHome();
    }

    /**
     * Add goods in order
     * @param int $goods_id
     * @return Response|string
     */
    public function actionAddGoodsOrder($goods_id)
    {

        if (!isset($_COOKIE['order_id'])|| empty($_COOKIE['order_id']))
            return $this->goHome();

        $id = (int)$_COOKIE['order_id'];

        $Orders = Orders::findOne($id);
        $Goods = Goods::findOne($goods_id);
        $OrdersGoods = new OrdersGoods();

        $OrdersGoods->orders_id = $Orders->id;
        $OrdersGoods->price_out = $Goods->price_out;
        $OrdersGoods->count = 1;
        $OrdersGoods->goods_id = $goods_id;

        $Orders->total = $Orders->total + $OrdersGoods->price_out;

        $PlacesGoods = PlacesGoods::findOne(['places_id' => $Orders->places_id, 'goods_id' => $OrdersGoods->goods_id]);

        if ($PlacesGoods->count >= $OrdersGoods->count) {

            $PlacesGoods->count = $PlacesGoods->count - $OrdersGoods->count;

            if ($Orders->save() && $OrdersGoods->save() && $PlacesGoods->save()) {
                // ADD NOTICE
            }
        }

        return $this->goHome();

    }

    /**
     * Close order
     * @param int $id
     * @return Response|string
     */
    public function actionCloseOrder($id)
    {

        if (isset($_COOKIE['order_id']) && $_COOKIE['order_id'] == $id) {
            unset($_COOKIE['order_id']);
            setcookie('order_id', null, time() - 1, '/');
        }

        return $this->goHome();
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    //...

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('request-password-reset', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token = false)
    {

        if ($token == false) return $this->goHome();

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
