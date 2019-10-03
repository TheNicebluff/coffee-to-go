<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Expenses */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Расходы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$attributes = [
    'id',
    [
        'attribute' => 'places_id',
        'format' => 'html',
        'value' => function ($data) {
            return Html::a($data->places->name, ['/places/view', 'id' => $data->places->id]);
        }
    ],
    [
        'attribute' => 'users_id',
        'format' => 'html',
        'value' => function ($data) {
            return $data->users->last_name . ' ' . $data->users->first_name . ' (' . $data->users->email . ')';
        }
    ],
    [
        'attribute' => 'expenses_type_id',
        'format' => 'html',
        'value' => function ($data) {
            return $data->expensesType->name;
        }
    ],
    'name',
    'price',
    'date',
    'date_add',
];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="">
            <h1 class="h2 pull-left"><?= Html::encode($this->title) ?></h1>
            <span class="pull-right">
                <?= Html::a(Html::tag('span', '', ['class' => "far fa-edit"]), ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
                <?= Html::a(Html::tag('span', '', ['class' => "far fa-trash-alt"]), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить?',
                        'method' => 'post',
                    ],
                ]) ?>
            </span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table_list'],
            'attributes' => $attributes,
        ]) ?>
    </div>
</div>