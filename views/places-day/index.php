<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $Places array */
/* @var $Users array */
/* @var $searchModel app\models\PlacesDay_Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рабочие дни';
$this->params['breadcrumbs'][] = $this->title;

$UsersArray = [];
foreach ($Users as $user) {
    $UsersArray[$user['id']] = $user['first_name'] . ' ' . $user['last_name'];
}

$Currency = Yii::$app->params['currency'];

$columns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:60px'],
        'filter' => false
    ],
    [
        'attribute' => 'places_id',
        'format' => 'html',
        'filter' => ArrayHelper::map($Places, 'id', 'name'),
        'value' => function ($data) {
            return Html::a($data->places->name, ['places/view', 'id' => $data->places->id]);
        }
    ],
    [
        'attribute' => 'users_id',
        'format' => 'html',
        'filter' => $UsersArray,
        'visible' => ((Yii::$app->user->identity->role == 'admin') ? 1 : 0),
        'value' => function ($data) {
            return Html::a($data->users->last_name . ' ' . $data->users->first_name . ' (' . $data->users->email . ')', ['users/view', 'id' => $data->places->id]);
        }
    ],
    [
        'attribute' => 'date',
        'headerOptions' => ['style' => 'width:192px'],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'name' => 'PlacesDay_Search[date]',
            'value' => ArrayHelper::getValue($_GET, "PlacesDay_Search.date"),
            'pluginOptions' => ['format' => 'yyyy-mm-dd', 'autoclose' => true]
        ]),
    ],
    [
        'attribute' => 'date_add',
        'headerOptions' => ['style' => 'width:192px'],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'name' => 'PlacesDay_Search[date_add]',
            'value' => ArrayHelper::getValue($_GET, "PlacesDay_Search.date_add"),
            'pluginOptions' => ['format' => 'yyyy-mm-dd', 'autoclose' => true]
        ]),
    ],
    [
        'attribute' => 'cash',
        'filter' => false,
        'value' => function ($data) use ($Currency) {
            return $data->cash . ' ' . $Currency;
        }
    ],
    [
        'attribute' => 'coffee_counter',
        'filter' => false
    ],
    [
        'attribute' => 'type',
        'format' => 'html',
        'filter' => ['open' => 'Открытие дня', 'close' => 'Закрытие дня'],
        'value' => function ($data) {
            $arr = ['open' => 'Открытие дня', 'close' => 'Закрытие дня'];
            return $arr[$data->type];
        }
    ]
];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="">
            <h1 class="h2 pull-left"><?= Html::encode($this->title) ?></h1>
            <span class="pull-right">
                <?= Html::a(Html::tag('i', '', ['class' => 'fas fa-plus']), ['create'], ['class' => 'btn btn-sm btn-success']) ?>
            </span>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="table-responsive ">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table_list'],
                'columns' => $columns,
            ]); ?>
        </div>
    </div>
</div>