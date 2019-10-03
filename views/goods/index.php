<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Goods_Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Продукты';
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
        'attribute' => 'type',
        'headerOptions' => ['style' => 'width:15%'],
        'format' => 'html',
        'filter' => Yii::$app->params['goods_type'],
        'value' => function ($data) {
            return Yii::$app->params['goods_type'][$data->type];
        }
    ],
    [
        'attribute' => 'unit',
        'headerOptions' => ['style' => 'width:15%'],
        'format' => 'html',
        'filter' => Yii::$app->params['unit'],
        'value' => function ($data) {
            return Yii::$app->params['unit'][$data->unit]['name'];
        }
    ],
    [
        'attribute' => 'price_in',
        'headerOptions' => ['style' => 'width:15%'],
        'filter' => false,
        'value' => function ($data) use ($Currency) {
            return $data->price_in . ' ' . $Currency;
        }
    ],
    [
        'attribute' => 'price_out',
        'headerOptions' => ['style' => 'width:15%'],
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
        <div class="table-responsive">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table_list'],
                'columns' => $columns,
            ]); ?>
        </div>
    </div>
</div>