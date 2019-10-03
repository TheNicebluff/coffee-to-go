<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Orders_Search */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $Places \app\models\Places */
/* @var $Users \app\models\Users */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;

$Currency = Yii::$app->params['currency'];

$UsersArray = [];
foreach ($Users as $user) {
    $UsersArray[$user['id']] = $user['first_name'] . ' ' . $user['last_name'];
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
        'update' => function ($url) {
            return false;
        }
    ]
];

$total = 0;
$models = $dataProvider->getModels();
foreach ($models as $data) {
    $total += $data->total;
}

$columns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:60px'],
        'filter' => false
    ],
    [
        'attribute' => 'date_add',
        'headerOptions' => ['style' => 'width:192px'],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'name' => 'Orders_Search[date_add]',
            'value' => ArrayHelper::getValue($_GET, "Orders_Search.date_add"),
            'pluginOptions' => ['format' => 'yyyy-mm-dd', 'autoclose' => true]
        ]),
    ],
    [
        'attribute' => 'places_id',
        'format' => 'html',
        'filter' => ArrayHelper::map($Places, 'id', 'name'),
        'visible' => ((Yii::$app->user->identity->role == 'admin') ? 1 : 0),
        'value' => function ($data) {
            return Html::a($data->places->name, ['places/view', 'id' => $data->places->id]);
        }
    ],
    [
        'attribute' => 'users_id',
        'format' => 'html',
        'filter' => $UsersArray,
        'value' => function ($data) {
            return Html::a($data->users->last_name . ' ' . $data->users->first_name . ' (' . $data->users->email . ')', ['users/view', 'id' => $data->places->id]);
        }
    ],
    [
        'attribute' => 'sum',
        'label' => 'Товар',
        'format' => 'html',
        'filter' => false,
        'value' => function ($data) {
            $res = '';
            if ($data->ordersGoods) {
                foreach ($data->ordersGoods as $item) {
                    $res .= $item->goods->name . ' (' . $item->count . ')<br>';
                }
            }
            if ($data->ordersProducts) {
                foreach ($data->ordersProducts as $item) {
                    $res .= $item->products->name . ' (' . $item->count . ')<br>';
                }
            }
            return $res;
        }
    ],
    [
        'attribute' => 'total',
        'format' => 'html',
        'filter' => false,
        'value' => function ($data) use ($Currency) {
            return $data->total;
        },
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
            <div class="col-xs-12 col-md-6">
                <div style="margin-bottom: 10px;">
                    <?= ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => $columns,
                        'exportConfig' => [
                            ExportMenu::FORMAT_EXCEL_X => ['icon' => 'fa fa-file-excel'],
                            ExportMenu::FORMAT_PDF => ['icon' => 'fa fa-file-pdf'],
                            ExportMenu::FORMAT_HTML => false,
                            ExportMenu::FORMAT_CSV => false,
                            ExportMenu::FORMAT_TEXT => false,
                            ExportMenu::FORMAT_EXCEL => false,
                        ]
                    ]); ?>
                </div>
            </div>

            <?php if (isset($_GET['Orders_Search'], $_GET['Orders_Search']['date_add']) && !empty($_GET['Orders_Search']['date_add'])) { ?>
                <div class="col-xs-12 col-md-6 text-right">
                    <h4>Заказов на: <b><?php echo $total; ?></b> <?php echo $Currency; ?></h4>
                </div>
            <?php } ?>

        </div>

        <div class="table-responsive ">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table_list'],
                'columns' => $columns
            ]); ?>
        </div>

    </div>
</div>