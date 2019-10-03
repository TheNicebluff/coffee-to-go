<?php

namespace app\controllers;

use app\models\Goods;
use app\models\Places;
use app\models\PlacesGoods;
use app\models\Users;
use Yii;
use app\models\ChargeOffGoods;
use app\models\ChargeOffGoods_Search;
use yii\web\NotFoundHttpException;

/**
 * ChargeOffGoodsController implements the CRUD actions for ChargeOffGoods model.
 */
class ChargeOffGoodsController extends AppController
{

    public $action_lists = ['index', 'view', 'create', 'delete'];

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
     * Lists all ChargeOffGoods models.
     * @return mixed
     */
    public function actionIndex()
    {

        $queryParams = Yii::$app->request->queryParams;
        if ($this->isUser()) {
            $queryParams['ChargeOffGoods_Search']['places_id'] = Yii::$app->user->identity->places_id;
        }

        $searchModel = new ChargeOffGoods_Search();
        $dataProvider = $searchModel->search($queryParams);

        $array = [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Goods' => Goods::find()->asArray()->all(),
            'Places' => Places::find()->asArray()->all(),
            'Users' => Users::find()->asArray()->all()
        ];

        return $this->render('index', $array);
    }

    /**
     * Displays a single ChargeOffGoods model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        return $this->render('view', [
            'model' => $this->findModel($id),
            'Users' => Users::find()->asArray()->all(),
            'Goods' => Goods::find()->asArray()->all(),
            'Places' => Places::find()->asArray()->all(),
        ]);
    }

    /**
     * Creates a new ChargeOffGoods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ChargeOffGoods();

        if ($model->load(Yii::$app->request->post())) {

            $model->date_add = date('Y-m-d H:i:s');
            $Units = Yii::$app->params['unit'];

            /* ROLE USERS */
            if ($this->isUser()) {
                $model->places_id = Yii::$app->user->identity->places_id;
                $model->users_id = Yii::$app->user->identity->id;
            }

            foreach ($model->places->placesGoods as $item) {
                if ($model->goods_id == $item->goods_id) {
                    if ($model->count <= $item->count) {
                        $item->count = $item->count - $model->count;
                        if ($item->save()) {
                            if ($model->save()) {
                                Yii::$app->session->setFlash('success', 'Списание успешно!');
                                return $this->redirect(['view', 'id' => $model->id]);
                            }
                        }
                        break;
                    } else {
                        Yii::$app->session->setFlash('danger', 'Остаток в заведении <b>' . $model->places->name . '</b> продукции <b>' . $model->goods->name . '</b> (<b>' . $item->count . '</b> ' . $Units[$model->goods->unit]['name'] . ') вы пытаетесь списать (<b>' . $model->count . '</b> ' . $Units[$model->goods->unit]['name'] . ')');
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'Users' => Users::find()->asArray()->all(),
            'Goods' => Goods::find()->asArray()->all(),
            'Places' => Places::find()->asArray()->all(),
        ]);
    }

    /**
     * Deletes PlacesGoods model.
     * @param integer $id
     * @redirect mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        /* ROLE USERS */
        if ($this->isUser()) {
            if ($this->isPlace($model->places_id)) {
                return $this->redirect(['index']);
            }
        }

        $pgModel = PlacesGoods::findOne(['places_id' => $model->places_id, 'goods_id' => $model->goods_id]);
        $pgModel->count = $pgModel->count + $model->count;
        if ($pgModel->save()) {
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ChargeOffGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ChargeOffGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChargeOffGoods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
