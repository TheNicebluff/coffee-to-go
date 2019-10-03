<?php

namespace app\controllers;

use app\models\Places;
use app\models\Users;
use Yii;
use app\models\PlacesDay;
use app\models\PlacesDay_Search;
use yii\web\NotFoundHttpException;

/**
 * PlacesDayController implements the CRUD actions for PlacesDay model.
 */
class PlacesDayController extends AppController
{

    public $action_lists = ['index', 'create', 'delete'];

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
     * Lists all PlacesDay models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlacesDay_Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Places' => Places::find()->asArray()->all(),
            'Users' => Users::find()->where(['role' => 'user'])->asArray()->all(),
        ]);
    }

    /**
     * Creates a new PlacesDay model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PlacesDay();

        if ($model->load(Yii::$app->request->post())) {

            if ($this->isUser()) {
                $model->users_id = Yii::$app->user->identity->id;
            }

            $model->date_add = date('Y-m-d H:i:s');
            $model->places_id = Users::findOne(['id' => $model->users_id])->places_id;

            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'Users' => Users::find()->where(['role' => 'user'])->asArray()->all(),
        ]);
    }

    /**
     * Deletes an existing PlacesDay model.
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
     * Finds the PlacesDay model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlacesDay the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlacesDay::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
