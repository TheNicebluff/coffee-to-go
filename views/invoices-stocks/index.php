<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $Users array */
/* @var $Stocks array */
/* @var $searchModel app\models\InvoicesStocks_Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Инвойсы склада';
$this->params['breadcrumbs'][] = $this->title;

$Units = Yii::$app->params['unit'];
$Currency = Yii::$app->params['currency'];

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
            $icon = Html::tag('span', '', ['class' => "far fa-edit"]);
            return Html::a($icon, $url,
                ['class' => 'btn_action btn btn-sm btn-info']);
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
        'label' => 'ФИО',
        'filter' => $UsersArray,
        'value' => function ($data) {
            return trim($data->users->first_name . ' ' . $data->users->last_name);
        }
    ],
    'name',
    [
        'attribute' => 'InvoicesGoodsStocks',
        'format' => 'html',
        'label' => 'Товар',
        'value' => function ($data) use ($Currency, $Units) {
            $str = '';
            foreach ($data->invoicesGoodsStocks as $ig) {
                $str_item = '<b>' . Html::a($ig->goods->name, ['/goods/view', 'id' => $ig->goods->id]) . ': </b>';
                $str_item .= $ig->count . ' ' . $Units[$ig->goods->unit]['name'];
                $str_item .= ' по ';
                $str_item .= $ig->price_in . ' ' . $Currency . '';
                $str .= Html::tag('p', $str_item, ['class' => '']);
            }
            return $str;
        }
    ],
    [
        'attribute' => 'InvoicesGoodsStocksTotal',
        'headerOptions' => ['style' => 'width:192px'],
        'label' => 'Сумма',
        'value' => function ($data) use ($Currency) {
            $price = 0;
            foreach ($data->invoicesGoodsStocks as $ig) {
                $price += $ig->count * $ig->price_in;
            }
            return $price . ' ' . $Currency;
        }
    ],
    [
        'attribute' => 'date',
        'headerOptions' => ['style' => 'width:192px'],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'name' => 'Invoices_Search[date]',
            'value' => ArrayHelper::getValue($_GET, "Invoices_Search.date"),
            'pluginOptions' => ['format' => 'yyyy-mm-dd', 'autoclose' => true]
        ]),
        'value' => function ($data) {
            return $data->date;
        }
    ],
    [
        'attribute' => 'date_add',
        'headerOptions' => ['style' => 'width:192px'],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'name' => 'Invoices_Search[date_add]',
            'value' => ArrayHelper::getValue($_GET, "Invoices_Search.date_add"),
            'pluginOptions' => ['format' => 'yyyy-mm-dd', 'autoclose' => true]
        ]),
    ],
    [
        'attribute' => 'stocks_id',
        'format' => 'html',
        'filter' => ArrayHelper::map($Stocks, 'id', 'name'),
        'value' => function ($data) {
            return Html::a($data->stocks->name, ['stocks/view', 'id' => $data->stocks->id]);
        }
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

<?/*
$this->title = 'Invoices Stocks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoices-stocks-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Invoices Stocks', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'users_id',
            'stocks_id',
            'date',
            'date_add',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); */?>


</div>
