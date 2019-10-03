<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Invoices */
/* @var $Goods \app\models\Goods */
/* @var $InvoicesGoods \app\models\InvoicesGoods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Инвойсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$Units = Yii::$app->params['unit'];
$Currency = Yii::$app->params['currency'];

$GoodsArray = [];
$GoodsUnitArray = [];
foreach ($Goods as $good) {
    $GoodsArray[$good['id']] = $good['name'] . ' (' . $Units[$good['unit']]['name'] . ')';
    $GoodsUnitArray[$good['id']] = ['data-unit' => $good['unit']];
}

$attributes = [
    'id',
    'name',
    [
        'attribute' => 'users_id',
        'format' => 'html',
        'value' => function ($data) {
            return $data->users->last_name . ' ' . $data->users->first_name . ' (' . $data->users->email . ')';
        }
    ],
    [
        'attribute' => 'places_id',
        'format' => 'html',
        'value' => function ($data) {
            return $data->places->name;
        }
    ],
    [
        'attribute' => 'stocks_id',
        'format' => 'html',
        'value' => function ($data) {
            if ($data->stocks_id) {
                return $data->stocks->name;
            }
            return '';
        }
    ],
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
    <div class="col-xs-12 col-lg-6">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table_list'],
            'attributes' => $attributes,
        ]) ?>
    </div>
    <div class="col-xs-12 col-lg-6">

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
            <?php $price = 0; ?>
            <?php if ($model->invoicesGoods) { ?>
                <tbody>
                <?php foreach ($model->invoicesGoods as $item) {

                    $price += ($item->price_in * $item->count); ?>
                    <tr>
                        <td><?= $item->id; ?></td>
                        <td><?= Html::a($item->goods->name, ['/goods/view', 'id' => $item->goods->id]); ?></td>
                        <td><?= $item->count . ' ' . $Units[$item->goods->unit]['name']; ?></td>
                        <td><?= $item->price_in . ' ' . $Currency; ?></td>
                        <td><?= $item->price_in * $item->count . ' ' . $Currency; ?></td>
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
            <tfoot>
            <tr>
                <th></th>
                <th colspan="3">Итого:</th>
                <th><?= $price . ' ' . $Currency; ?></th>
                <th></th>
            </tr>
            </tfoot>
        </table>

        <?php $form = ActiveForm::begin(); ?>
        <table class="table ">
            <tbody>
            <tr class="">
                <th width="50%">
                    <?= $form->field($InvoicesGoods, 'goods_id')->widget(Select2::classname(), [
                        'data' => $GoodsArray,
                        'options' => [
                            'placeholder' => 'Продукт',
                            'options' => $GoodsUnitArray
                        ],
                        'pluginEvents' => [
                            "select2:select" => "function(e) {
                                var data = e.params.data;
                                var thisID = data.id;
                            }"]
                    ]); ?>
                </th>
                <th><?= $form->field($InvoicesGoods, 'count')->textInput(['type' => 'number']); ?></th>
                <th><?= $form->field($InvoicesGoods, 'price_in')->textInput(['type' => 'number', 'step' => '0.01'])->label('Сумма'); ?></th>
            </tr>
            <tr>
                <th><?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?></th>
                <th colspan="2">
                    <div id="data-info" class="alert alert-info"></div>
                </th>
            </tr>
            </tbody>
        </table>

        <?php ActiveForm::end(); ?>

    </div>
</div>