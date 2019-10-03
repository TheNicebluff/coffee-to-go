<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $Users array */
/* @var $Places array */
/* @var $Stocks array */
/* @var $searchModel app\models\Invoices_Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Инвойсы';
$this->params['breadcrumbs'][] = $this->title;

$Units = Yii::$app->params['unit'];
$Currency = Yii::$app->params['currency'];

$total = 0;
$models = $dataProvider->getModels();
foreach ($models as $data) {
    $price = 0;
    foreach ($data->invoicesGoods as $ig) {
        $price += $ig->count * $ig->price_in;
    }
    $total += $price;
}

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

$StocksArray = ArrayHelper::map($Stocks, 'id', 'name');
array_unshift($StocksArray, "Не со склада");

$role = Yii::$app->user->identity->role;

$columns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:60px'],
        'filter' => false
    ],
    [
        'attribute' => 'users_id',
        'format' => 'html',
        'visible' => (($role == 'admin') ? 1 : 0),
        'filter' => $UsersArray,
        'value' => function ($data) {
            return trim($data->users->first_name . ' ' . $data->users->last_name . ' ' . $data->users->email);
        }
    ],
    [
        'attribute' => 'name',
        'value' => function ($data) {
            return $data->name;
        }
    ],
    [
        'attribute' => 'invoicesGoods',
        'format' => 'html',
        'label' => 'Товар',
        'value' => function ($data) use ($Currency, $Units, $role) {
            $str = '';
            foreach ($data->invoicesGoods as $ig) {
                $str_item = ($role == 'admin') ? '<b>' . Html::a($ig->goods->name, ['/goods/view', 'id' => $ig->goods->id]) . ': </b>' : '<b>' . $ig->goods->name . ': </b>';
                $str_item .= $ig->count . ' ' . $Units[$ig->goods->unit]['name'];
                $str_item .= ' по ';
                $str_item .= $ig->price_in . ' ' . $Currency . '';
                $str .= Html::tag('p', $str_item, ['class' => '']);
            }
            return $str;
        }
    ],
    [
        'attribute' => 'invoicesGoodsTotal',
        'headerOptions' => ['style' => 'width:192px'],
        'label' => 'Сумма',
        'value' => function ($data) use ($Currency) {
            $price = 0;
            foreach ($data->invoicesGoods as $ig) {
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
        'visible' => (($role == 'admin') ? 1 : 0),
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'name' => 'Invoices_Search[date_add]',
            'value' => ArrayHelper::getValue($_GET, "Invoices_Search.date_add"),
            'pluginOptions' => ['format' => 'yyyy-mm-dd', 'autoclose' => true]
        ]),
    ],
    [
        'attribute' => 'places_id',
        'format' => 'html',
        'filter' => ArrayHelper::map($Places, 'id', 'name'),
        'visible' => (($role == 'admin') ? 1 : 0),
        'value' => function ($data) {
            return Html::a($data->places->name, ['places/view', 'id' => $data->places->id]);
        }
    ],
    [
        'attribute' => 'stocks_id',
        'format' => 'html',
        'filter' => $StocksArray,
        'visible' => (($role == 'admin') ? 1 : 0),
        'value' => function ($data) {
            if (empty($data->stocks_id)) return '';
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

        <div class="row">
            <?php if (isset($_GET['Invoices_Search'], $_GET['Invoices_Search']['date']) && !empty($_GET['Invoices_Search']['date'])) { ?>
                <div class="col-xs-12 text-right">
                    <h4>Инвойсов на: <b><?php echo $total; ?></b> <?php echo $Currency; ?></h4>
                </div>
            <?php } ?>
        </div>

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