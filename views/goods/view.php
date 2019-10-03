<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Goods */
/* @var $InvoicesGoods app\models\InvoicesGoods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$Units = Yii::$app->params['unit'];
$Currency = Yii::$app->params['currency'];
$GoodsType = Yii::$app->params['goods_type'];

$attributes = [
    'id',
    'name',
    [
        'attribute' => 'unit',
        'format' => 'html',
        'value' => function ($data) use ($Units) {
            return $Units[$data->unit]['name'];
        }
    ],
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
        'attribute' => 'type',
        'format' => 'html',
        'value' => function ($data) use ($GoodsType) {
            return $GoodsType[$data->type];
        }
    ],
    [
        'attribute' => 'price_in',
        'format' => 'html',
        'value' => function ($data) use ($Currency) {
            return $data->price_in . ' ' . $Currency;
        }
    ],
    [
        'attribute' => 'price_out',
        'format' => 'html',
        'value' => function ($data) use ($Currency) {
            return $data->price_out . ' ' . $Currency;
        }
    ]
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

<div class="row">
    <div class="col-xs-12">
        <div class="">
            <h1 class="h2 pull-left">Приход товара</h1>
            <table class="table table-hover table_list">
                <thead class="">
                <tr>
                    <th width="60">ID</th>
                    <th>Инвойс</th>
                    <th>Дата</th>
                    <th>Заведение</th>
                    <th>Кол-во</th>
                    <th>Цена</th>
                </tr>
                </thead>
                <?php if ($InvoicesGoods) { ?>
                    <tbody>
                    <?php foreach ($InvoicesGoods as $item) { ?>
                        <tr>
                            <td><?= $item->id; ?></td>
                            <td><?= Html::a($item->invoices->name, ['/invoices/view', 'id' => $item->invoices->id]); ?></td>
                            <td><?= $item->invoices->date; ?></td>
                            <td><?= Html::a($item->invoices->places->name, ['/places/view', 'id' => $item->invoices->places->id]); ?></td>
                            <td><?= $item->count; ?> <?= $Units[$item->goods->unit]['name']; ?></td>
                            <td><?= $item->price_in; ?> <?= $Currency; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                <?php } ?>
            </table>
        </div>
    </div>
</div>