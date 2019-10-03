<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $Places \app\models\Places */
/* @var $searchModel app\models\Users_Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сотрудники';
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
    [
        'attribute' => 'first_name',
        'format' => 'html',
        'label' => 'ФИО',
        'value' => function ($data) {
            return trim($data->first_name . ' ' . $data->last_name);
        }
    ],
    'email:email',
    [
        'attribute' => 'role',
        'format' => 'html',
        'filter' => Yii::$app->params['role'],
        'value' => function ($data) {
            return Yii::$app->params['role'][$data->role];
        }
    ],
    [
        'attribute' => 'places_id',
        'format' => 'html',
        'filter' => ArrayHelper::map($Places, 'id', 'name'),
        'value' => function ($data) {
            return Html::a($data->places->name, ['places/view', 'id' => $data->places->id]);
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