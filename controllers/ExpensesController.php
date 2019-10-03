<?php

namespace app\controllers;

use Yii;
use yii\base\ActionEvent;
use yii\web\NotFoundHttpException;
use app\models\ExpensesType;
use app\models\Places;
use app\models\Users;
use app\models\Expenses;
use app\models\Expenses_Search;

/**
 * ExpensesController implements the CRUD actions for Expenses model.
 */
class ExpensesController extends AppController
{
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
     * Lists all Expenses models.
     * @return mixed
     */
    public function actionIndex()
    {

        $queryParams = Yii::$app->request->queryParams;
        if ($this->isUser()) {
            $queryParams['Expenses_Search']['places_id'] = Yii::$app->user->identity->places_id;
        }

        $searchModel = new Expenses_Search();
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Places' => Places::find()->all(),
            'Users' => Users::find()->all(),
            'ExpensesType' => ExpensesType::find()->all(),
        ]);
    }

    /**
     * Displays a single Expenses model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($this->isUser()) {
            if (!$this->isPlace($model->places_id)) {
                return $this->redirect(['/expenses']);
            }
        }

        return $this->render('view', [
            'model' => $model,
            'Users' => Users::find()->asArray()->all(),
            'Places' => Places::find()->asArray()->all(),
            'ExpensesType' => ExpensesType::find()->asArray()->all(),
        ]);
    }

    /**
     * Creates a new Expenses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Expenses();


        if ($model->load(Yii::$app->request->post())) {

            if ($this->isUser()) {
                $model->places_id = Yii::$app->user->identity->places_id;
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'Places' => Places::find()->all(),
            'ExpensesType' => ExpensesType::find()->all(),
        ]);
    }

    /**
     * Updates an existing Expenses model.
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
                return $this->redirect(['/orders']);
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'Places' => Places::find()->asArray()->all(),
            'ExpensesType' => ExpensesType::find()->asArray()->all(),
        ]);
    }

    /**
     * Deletes an existing Expenses model.
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
     * Finds the Expenses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Expenses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expenses::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
