<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ChargeOffGoods_Search */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $Users array */
/* @var $Places array */
/* @var $Goods array */

$this->title = 'Списаные продукты';
$this->params['breadcrumbs'][] = $this->title;

$columns_edit = [
    'class' => 'yii\grid\ActionColumn',
    'headerOptions' => ['style' => 'width:131px'],
    'buttons' => [
        'view' => function ($url) {
            $icon = Html::tag('span', '', ['class' => "far fa-eye"]);
            return Html::a($icon, $url,
                ['class' => 'btn_action btn btn-sm btn-primary']);
        },
        'update' => function ($url) {
            return false;
        },
        'delete' => function ($url) {
            $icon = Html::tag('span', '', ['class' => "far fa-trash-alt"]);
            return Html::a($icon, $url,
                [
                    'class' => 'btn_action btn btn-sm btn-danger',
                    'data-pjax' => 0,
                    'data-method' => 'post',
                    'data-confirm' => 'Вы уверены, что хотите удалить?'
                ]);
        },
    ],
];

$UsersArray = [];
foreach ($Users as $user) {
    $UsersArray[$user['id']] = $user['first_name'] . ' ' . $user['last_name'];
}

$columns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:60px'],
        'filter' => false
    ],
    [
        'attribute' => 'users_id',
        'format' => 'html',
        'filter' => $UsersArray,
        'visible' => ((Yii::$app->user->identity->role == 'admin') ? 1 : 0),
        'value' => function ($data) {
            return Html::a($data->users->first_name . ' ' . $data->users->last_name, ['users/view', 'id' => $data->users->id]);
        }
    ],
    [
        'attribute' => 'places_id',
        'format' => 'html',
        'filter' => ArrayHelper::map($Places, 'id', 'name'),
        'value' => function ($data) {
            if (Yii::$app->user->identity->places_id == 'admin') {
                return Html::a($data->places->name, ['places/view', 'id' => $data->places->id]);
            } else {
                return $data->places->name;
            }
        }
    ],
    [
        'attribute' => 'goods_id',
        'format' => 'html',
        'filter' => ArrayHelper::map($Goods, 'id', 'name'),
        'value' => function ($data) {
            if (Yii::$app->user->identity->places_id == 'admin') {
                return Html::a($data->goods->name, ['goods/view', 'id' => $data->goods->id]);
            } else {
                return $data->goods->name;
            }
        }
    ],
    [
        'attribute' => 'count',
        'headerOptions' => ['style' => 'width:192px']
    ],
    [
        'attribute' => 'date_add',
        'headerOptions' => ['style' => 'width:192px'],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'name' => 'ChargeOffGoods_Search[date_add]',
            'value' => ArrayHelper::getValue($_GET, "ChargeOffGoods_Search.date_add"),
            'pluginOptions' => ['format' => 'yyyy-mm-dd', 'autoclose' => true]
        ])
    ],
    $columns_edit
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