<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\InvoicesStocks */
/* @var $Goods \app\models\Goods */
/* @var $InvoicesGoodsStocks \app\models\InvoicesGoodsStocks */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Инвойсы склада', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$Units = Yii::$app->params['unit'];
$Currency = Yii::$app->params['currency'];

$GoodsArray = [];
foreach ($Goods as $good) {
    $GoodsArray[$good['id']] = $good['name'] . ' (' . $Units[$good['unit']]['name'] . ')';
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
        'attribute' => 'stocks_id',
        'format' => 'html',
        'value' => function ($data) {
            return $data->stocks->name;
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
            <?php if ($model->invoicesGoodsStocks) { ?>
                <tbody>
                <?php foreach ($model->invoicesGoodsStocks as $item) {

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
                                ['delete-goods', 'id' => $item->id, 'goods_id' => $item->goods_id, 'stocks_id' => $model->stocks_id, 'invoices_stocks_id' => $model->id],
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
                    <?= $form->field($InvoicesGoodsStocks, 'goods_id')->widget(Select2::classname(), [
                        'data' => $GoodsArray,
                        'options' => ['placeholder' => 'Продукт'],
                        'pluginEvents' => [
                            "select2:select" => "function(e) {
                                var data = e.params.data;
                                var thisID = data.id;
                            }"]
                    ]); ?>
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?></th>
                <th><?= $form->field($InvoicesGoodsStocks, 'count')->textInput(['type' => 'number']); ?></th>
                <th colspan="2"><?= $form->field($InvoicesGoodsStocks, 'price_in')->textInput(['type' => 'number', 'step' => '0.01'])->label('Сумма'); ?></th>
            </tr>
            </tbody>
        </table>
        <?php ActiveForm::end(); ?>

    </div>
</div>