<?php

namespace app\controllers;

use Yii;
use yii\base\ActionEvent;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Log;

/**
 * @param $places_id
 * @param $role
 * AppController extends Controller
 */
class AppController extends Controller
{


    public $action_lists = ['index', 'view', 'create', 'update', 'delete'];
    public $layout = 'main';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => $this->action_lists,
                        'allow' => false,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {

        if (!Yii::$app->user->isGuest) {
/*
            if ($this->isAdmin()) {
                $this->layout = 'admin';
            } else if ($this->isUser()) {
                $this->layout = 'user';
            }*/

            // Логирование всех запросов.
            if (Yii::$app->request->post()) {

                $arr = Yii::$app->request->post();

                if (isset($arr['_csrf']))
                    unset($arr['_csrf']);

                if (!empty($arr)) {

                    $Log = new Log();
                    if (isset($arr['LoginForm']))
                        unset($arr['LoginForm']['password']);

                    $Log->model = $this->id;
                    $Log->users_id = Yii::$app->user->identity->id;
                    $Log->action = $this->action->id;
                    $Log->date_add = date('Y-m-d H:i:s');

                    $Log->post = json_encode($arr);

                    $Log->save();
                }
            }
        }

        return parent::beforeAction($action);
    }

    /**
     * Get User object
     * @return mixed
     */
    public function getUser()
    {
        return Yii::$app->user->isGuest ? false : Yii::$app->user->identity;
    }

    /**
     * Get User Role
     * @return mixed
     */
    public function getUserRole()
    {
        return Yii::$app->user->isGuest ? false : $this->getUser()->role;
    }

    /**
     * Get User Role
     * @return true
     */
    public function isUser()
    {
        return $this->getUserRole() && $this->getUserRole() == 'user' ? true : false;
    }

    /**
     * Get User Role
     * @return true
     */
    public function isAdmin()
    {
        return $this->getUserRole() && $this->getUserRole() == 'admin' ? true : false;
    }

    /**
     * Get User Role
     * @places_id $places_id
     * @return true
     */
    public function isPlace($places_id)
    {
        return $this->getUser() && $this->getUser()->places_id == $places_id ? $places_id : false;
    }
}
