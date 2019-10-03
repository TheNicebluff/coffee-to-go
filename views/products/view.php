<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $Goods*/
/* @var $Places */
/* @var $model app\models\Products */

/* @var $ProductsGoods app\models\ProductsGoods */
/* @var $ProductsPlaces app\models\ProductsPlaces */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Техническая карта', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$Units = Yii::$app->params['unit'];
$Currency = Yii::$app->params['currency'];

$GoodsArray = [];
foreach ($Goods as $good) {
    $GoodsArray[$good['id']] = $good['name'] . ' | ' . $good['price_in'] . $Currency . ' | за 1 ' . $Units[$good['unit']]['name'];
}

$attributes = [
    'id',
    'name',
    [
        'attribute' => 'image',
        'format' => 'html',
        'value' => function ($data) {
            if (!empty($data->image)) {
                return Html::img('/web/uploads/' . $data->image, ['width' => '150']);
            }
            return '';
        }
    ],
    [
        'attribute' => 'price_out',
        'format' => 'html',
        'value' => function ($data) use ($Currency) {
            return $data->price_out . ' ' . $Currency;
        }
    ],
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
    <div class="col-xs-12 col-lg-6">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table_list'],
            'attributes' => $attributes,
        ]) ?>

        <?php $form = ActiveForm::begin(); ?>
        <table class="table table-hover table_list">
            <thead class="">
            <tr>
                <th>ID</th>
                <th>Заведения</th>
                <th width="60"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->productsPlaces as $item) {

                $PlacesItem = array_filter($Places, function ($k) use ($item) {
                    return $k['id'] == $item->places_id;
                });
                $PlacesItem = array_shift($PlacesItem);
                ?>
                <tr>
                    <td><?= $PlacesItem['id']; ?></td>
                    <td><?= $PlacesItem['name']; ?></td>
                    <td>
                        <?= Html::a(
                            Html::tag('span', '', ['class' => "far fa-trash-alt"]),
                            ['delete-places', 'places_id' => $item->places_id, 'products_id' => $item->products_id],
                            [
                                'class' => 'btn_action btn btn-sm btn-danger',
                                'data-pjax' => 0,
                                'data-method' => 'post',
                                'data-confirm' => 'Вы уверены, что хотите удалить?'
                            ]); ?>
                    </td>
                </tr>
            <? } ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="2"><?= $form->field($ProductsPlaces, 'places_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map($Places, 'id', 'name'),
                        'options' => ['placeholder' => 'Заведение'],
                        'pluginEvents' => [
                            "select2:select" => "function(e) {
                            var data = e.params.data;
                            var thisID = data.id;
                        }"]]); ?>
                </th>
            </tr>
            </tfoot>
        </table>
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>


    </div>
    <div class="col-xs-12 col-lg-6">
        <?php if ($model->productsGoods) {?>
            <table class="table table-hover table_list">
                <thead class="">
                <tr>
                    <th width="60">ID</th>
                    <th>Продукт</th>
                    <th width="160">Кол-во</th>
                    <th width="160">Цена закупки</th>
                    <th width="160">Сумма закупки</th>
                    <th width="60"></th>
                </tr>
                </thead>
                <tbody>
                <?
                $sum_in = $sum_out = 0;
                foreach ($model->productsGoods as $item) {

                    if (empty($item->goods_id)) continue;

                    $GoodsItem = array_filter($Goods, function ($k) use ($item) {
                        return $k['id'] == $item->goods_id;
                    });

                    $GoodsItem = array_shift($GoodsItem);
                    $GoodsItemUnit = $Units[$GoodsItem['unit']]['name'];
                    $sum_in += ($GoodsItem['price_in'] * $item->count); ?>
                    <tr>
                        <td><?= $item->id; ?></td>
                        <td><?= $GoodsItem['name']; ?></td>
                        <td><?= $item->count . ' ' . $GoodsItemUnit; ?></td>
                        <td><?= $GoodsItem['price_in'] . ' ' . $Currency . ' за 1 ' . $GoodsItemUnit; ?></td>
                        <td><?= ($item->count * $GoodsItem['price_in']) . ' ' . $Currency; ?></td>
                        <td>
                            <?= Html::a(
                                Html::tag('span', '', ['class' => "far fa-trash-alt"]),
                                ['delete-goods', 'id' => $item->id, 'products_id' => $model->id],
                                [
                                    'class' => 'btn_action btn btn-sm btn-danger',
                                    'data-pjax' => 0,
                                    'data-method' => 'post',
                                    'data-confirm' => 'Вы уверены, что хотите удалить?'
                                ]); ?>
                        </td>
                    </tr>
                <? } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th></th>
                    <th colspan="3">Итого</th>
                    <th><?= $sum_in . ' ' . $Currency; ?></th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
        <? } ?>

        <?php $form = ActiveForm::begin(['options' => ['class' => 'row']]); ?>

        <div class="col-xs-12 col-lg-6">
            <?= $form->field($ProductsGoods, 'goods_id')->widget(Select2::classname(), [
                'data' => $GoodsArray,
                'options' => ['placeholder' => 'Продукт'],
                'pluginEvents' => [
                    "select2:select" => "function(e) {
                    var data = e.params.data;
                    var thisID = data.id;
                }"]]); ?>
        </div>

        <div class="col-xs-12 col-lg-6">
            <?= $form->field($ProductsGoods, 'count')->textInput(['type' => 'number']); ?>
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>