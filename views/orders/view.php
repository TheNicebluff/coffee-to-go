<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $PlacesGoods \app\models\PlacesGoods */
/* @var $OrdersGoodsModel \app\models\OrdersGoods */
/* @var $ProductsPlaces \app\models\ProductsPlaces */
/* @var $OrdersProductsModel \app\models\OrdersProducts */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$Units = Yii::$app->params['unit'];
$Currency = Yii::$app->params['currency'];

$attributes = [
    'id',
    'date_add',
    [
        'attribute' => 'places_id',
        'format' => 'html',
        'value' => function ($data) {
            return Html::a($data->places->name, ['places/view', 'id' => $data->places->id]);
        }
    ],
    [
        'attribute' => 'users_id',
        'format' => 'html',
        'value' => function ($data) {
            return Html::a($data->users->last_name . ' ' . $data->users->first_name . ' (' . $data->users->email . ')', ['view', 'id' => $data->places->id]);
        }
    ],
    [
        'attribute' => 'total',
        'format' => 'html',
        'value' => function ($data) use ($Currency) {
            return $data->total . ' ' . $Currency;
        }
    ],
];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="">
            <h1 class="h2 pull-left">Заказ №<?= Html::encode($this->title) ?></h1>
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

    <?php
    echo date('H:i:s');
    $PlacesGoodsArray = [];
    foreach ($PlacesGoods as $item) {
        if ($item->goods->type == 'goods') {
            $PlacesGoodsArray[$item->goods_id] = $item->goods->name . ' (' . $item->count . ')';
        }
    } ?>

    <div class="col-xs-12 col-lg-6">
        <div class="">
            <h3 class="h2 pull-left">Продукты</h3>
        </div>
        <table class="table table-hover table_list">
            <thead class="">
            <tr>
                <th width="60">ID</th>
                <th>Продукт</th>
                <th width="120">Кол-во</th>
                <th width="120">Цена</th>
                <th width="120">Сумма</th>
                <th width="60"></th>
            </tr>
            </thead>
            <?php if ($model->ordersGoods) { ?>
                <tbody>
                <?php foreach ($model->ordersGoods as $item) { ?>
                    <tr>
                        <td><?= $item->goods_id; ?></td>
                        <td><?= $item->goods->name; ?></td>
                        <td><?= $item->count . ' ' . $Units[$item->goods->unit]['name']; ?></td>
                        <td><?= $item->price_out . ' ' . $Currency; ?></td>
                        <td><?= ($item->price_out * $item->count) . ' ' . $Currency; ?></td>
                        <td><?= Html::a(
                                Html::tag('span', '', ['class' => "far fa-trash-alt"]),
                                ['delete-goods', 'id' => $item->id],
                                [
                                    'class' => 'btn_action btn btn-sm btn-danger',
                                    'data-pjax' => 0,
                                    'data-method' => 'post',
                                    'data-confirm' => 'Вы уверены, что хотите удалить?'
                                ]); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            <?php } ?>
        </table>

        <? if (!empty($PlacesGoodsArray)) { ?>
            <?php $form = ActiveForm::begin(); ?>
            <table class="table ">
                <tbody>
                <tr class="">
                    <th width="50%">
                        <?= $form->field($OrdersGoodsModel, 'goods_id')->widget(Select2::classname(), [
                            'data' => $PlacesGoodsArray,
                            'pluginEvents' => [
                                "select2:select" => "function(e) {
                                var data = e.params.data;
                                var thisID = data.id;
                            }"]
                        ]); ?>
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?></th>
                    <th><?= $form->field($OrdersGoodsModel, 'count')->textInput(['type' => 'number']); ?></th>
                </tr>
                </tbody>
            </table>
            <?php ActiveForm::end(); ?>
        <? } ?>
    </div>


    <?php
    $ProductsPlacesArray = [];
    foreach ($ProductsPlaces as $item) {
        $ProductsPlacesArray[$item->products_id] = $item->products->name;
    } ?>
    <div class="col-xs-12 col-lg-6">
        <div class="">
            <h3 class="h2 pull-left">Товары</h3>
        </div>
        <table class="table table-hover table_list">
            <thead class="">
            <tr>
                <th width="60">ID</th>
                <th>Товар</th>
                <th>Ингридиенты</th>
                <th width="120">Кол-во</th>
                <th width="120">Цена</th>
                <th width="120">Сумма</th>
                <th width="60"></th>
            </tr>
            </thead>
            <?php if ($model->ordersProducts) { ?>
                <tbody>
                <?php foreach ($model->ordersProducts as $item) { ?>
                    <tr>
                        <td><?= $item->id; ?></td>
                        <td><?= $item->products->name; ?></td>
                        <td>
                            <? foreach ($item->products->productsGoods as $good) {
                                $str_item = '<b>' . Html::a($good->goods->name, ['/goods/view', 'id' => $good->goods->id]) . ': </b>';
                                $str_item .= $good->count . ' ' . $Units[$good->goods->unit]['name'];
                                echo Html::tag('p', $str_item, ['class' => '']);
                            } ?>
                        </td>
                        <td><?= $item->count; ?></td>
                        <td><?= $item->price_out . ' ' . $Currency; ?></td>
                        <td><?= ($item->price_out * $item->count) . ' ' . $Currency; ?></td>
                        <td><?= Html::a(
                                Html::tag('span', '', ['class' => "far fa-trash-alt"]),
                                ['delete-products', 'id' => $item->id],
                                [
                                    'class' => 'btn_action btn btn-sm btn-danger',
                                    'data-pjax' => 0,
                                    'data-method' => 'post',
                                    'data-confirm' => 'Вы уверены, что хотите удалить?'
                                ]); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            <?php } ?>
        </table>

        <? if (!empty($ProductsPlacesArray)) { ?>
            <?php $form = ActiveForm::begin(); ?>
            <table class="table ">
                <tbody>
                <tr class="">
                    <th width="50%">
                        <?= $form->field($OrdersProductsModel, 'products_id')->widget(Select2::classname(), [
                            'data' => $ProductsPlacesArray,
                            'pluginEvents' => [
                                "select2:select" => "function(e) {
                                var data = e.params.data;
                                var thisID = data.id;
                            }"]
                        ]); ?>
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?></th>
                    <th><?= $form->field($OrdersProductsModel, 'count')->textInput(['type' => 'number']); ?></th>
                </tr>
                </tbody>
            </table>
            <?php ActiveForm::end(); ?>
        <? } ?>
    </div>
</div>