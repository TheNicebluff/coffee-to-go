<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Products_Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Техническая карта';
$this->params['breadcrumbs'][] = $this->title;

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

$columns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:60px'],
        'filter' => false
    ],
    'name',
    [
        'attribute' => 'price_out',
        'headerOptions' => ['style' => 'width:192px'],
        'filter' => false,
        'value' => function ($data) use ($Currency) {
            return $data->price_out . ' ' . $Currency;
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