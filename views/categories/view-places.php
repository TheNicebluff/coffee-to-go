<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $this yii\web\View */
/* @var $model app\models\CategoriesPlaces */
/* @var $CategoriesPlacesProducts app\models\CategoriesPlacesProducts */
/* @var $CategoriesPlacesGoods app\models\CategoriesPlacesGoods */
/* @var $Goods array */
/* @var $Products array */

$this->title = $model->places->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->categories->name, 'url' => ['view', 'id' => $model->categories_id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$attributes = [
    'id',
    [
        'attribute' => 'places_id',
        'value' => function ($data) {
            return $data->places->name;
        }
    ],
    [
        'attribute' => 'categories_id',
        'value' => function ($data) {
            return $data->categories->name;
        }
    ],
];

?>
<div class="row">
    <div class="col-xs-12">
        <div class="">
            <h1 class="h2 pull-left"><?= Html::encode($this->title) ?></h1>
            <span class="pull-right">
                <?= Html::a(Html::tag('span', '', ['class' => "far fa-edit"]), ['view', 'id' => $model->categories_id], ['class' => 'btn btn-sm btn-primary']) ?>
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
        ]); ?>
    </div>

    <div class="col-xs-12 col-sm-6">

        <?php $form = ActiveForm::begin(); ?>
        <table class="table ">
            <tbody>
            <tr class="">
                <th width="50%">
                    <?= $form->field($CategoriesPlacesGoods, 'goods_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map($Goods, 'id', 'name'),
                        'pluginEvents' => [
                            "select2:select" => "function(e) {
                                var data = e.params.data;
                                var thisID = data.id;
                            }"]
                    ]); ?>
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-sm btn-success']) ?></th>
            </tr>
            </tbody>
        </table>
        <?php ActiveForm::end(); ?>

        <table class="table table-hover table_list">
            <thead class="">
            <tr>
                <th width="60">ID</th>
                <th>Продукция</th>
                <th width="60"></th>
            </tr>
            </thead>
            <?php if ($model->categoriesPlacesGoods) { ?>
                <tbody>
                <?php foreach ($model->categoriesPlacesGoods as $item) { ?>
                    <tr>
                        <td><?= $item->id; ?></td>
                        <td><?= $item->goods->name; ?></td>
                        <td>
                            <?= Html::a(
                                Html::tag('span', '', ['class' => "far fa-trash-alt"]),
                                ['delete-goods', 'id' => $item->id],
                                [
                                    'class' => 'btn_action btn btn-sm btn-danger',
                                    'data-pjax' => 0,
                                    'data-method' => 'post',
                                    'data-confirm' => 'Вы уверены, что хотите удалить?'
                                ]); ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            <?php } ?>
        </table>

    </div>

    <div class="col-xs-12 col-sm-6">

        <?php $form = ActiveForm::begin(); ?>
        <table class="table ">
            <tbody>
            <tr class="">
                <th width="50%">
                    <?= $form->field($CategoriesPlacesProducts, 'products_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map($Products, 'id', 'name'),
                        'pluginEvents' => [
                            "select2:select" => "function(e) {
                                var data = e.params.data;
                                var thisID = data.id;
                            }"]
                    ]); ?>
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-sm btn-success']) ?></th>
            </tr>
            </tbody>
        </table>
        <?php ActiveForm::end(); ?>

        <table class="table table-hover table_list">
            <thead class="">
            <tr>
                <th width="60">ID</th>
                <th>Товары</th>
                <th width="60"></th>
            </tr>
            </thead>
            <?php if ($model->categoriesPlacesProducts) { ?>
                <tbody>
                <?php foreach ($model->categoriesPlacesProducts as $item) { ?>
                    <tr>
                        <td><?= $item->id; ?></td>
                        <td><?= $item->products->name; ?></td>
                        <td>
                            <?= Html::a(
                                Html::tag('span', '', ['class' => "far fa-trash-alt"]),
                                ['delete-products', 'id' => $item->id],
                                [
                                    'class' => 'btn_action btn btn-sm btn-danger',
                                    'data-pjax' => 0,
                                    'data-method' => 'post',
                                    'data-confirm' => 'Вы уверены, что хотите удалить?'
                                ]); ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            <?php } ?>
        </table>

    </div>
</div>