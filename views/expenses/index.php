<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Expenses_Search */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $Users array */
/* @var $Places array */
/* @var $ExpensesType array */

$this->title = 'Расходы';
$this->params['breadcrumbs'][] = $this->title;

$Units = Yii::$app->params['unit'];
$Currency = Yii::$app->params['currency'];

$total = 0;
$models = $dataProvider->getModels();
foreach ($models as $data) {
    $total +=  $data->price;
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

$columns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:5%'],
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
    'name',
    [
        'attribute' => 'price',
        'format' => 'html',
        'filter' => false,
        'value' => function ($data) use ($Currency) {
            return $data->price . ' ' . $Currency;
        }
    ],
    [
        'attribute' => 'expenses_type_id',
        'format' => 'html',
        'filter' => ArrayHelper::map($ExpensesType, 'id', 'name'),
        'value' => function ($data) {
            return $data->expensesType->name;
        }
    ],
    [
        'attribute' => 'users_id',
        'format' => 'html',
        'filter' => $UsersArray,
        'value' => function ($data) {
            return $data->users->last_name . ' ' . $data->users->first_name . ' (' . $data->users->email . ')';
        }
    ],
    [
        'attribute' => 'date_add',
        'headerOptions' => ['style' => 'width:192px'],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'name' => 'Expenses_Search[date_add]',
            'value' => ArrayHelper::getValue($_GET, "Expenses_Search.date_add"),
            'pluginOptions' => ['format' => 'yyyy-mm-dd', 'autoclose' => true]
        ])
    ],
    [
        'attribute' => 'date',
        'headerOptions' => ['style' => 'width:192px'],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'name' => 'Expenses_Search[date]',
            'value' => ArrayHelper::getValue($_GET, "Expenses_Search.date"),
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

        <div class="row">
            <?php if (isset($_GET['Expenses_Search'], $_GET['Expenses_Search']['date']) && !empty($_GET['Expenses_Search']['date'])) { ?>
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