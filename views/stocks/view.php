<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Stocks */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Склады', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$Units = Yii::$app->params['unit'];
$Currency = Yii::$app->params['currency'];

$attributes = [
    'id',
    'name',
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
        ]); ?>
    </div>

    <div class="col-xs-12">
        <h2 class="mb-3">Остаток на складе</h2>
        <?php if ($model->stocksGoods) { ?>
            <table class="table table-hover table_list">
                <thead class="">
                <tr>
                    <th width="60">ID</th>
                    <th>Продукция</th>
                    <th width="120">Остаток</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($model->stocksGoods as $item) { ?>
                    <tr>
                        <th width="60"><?= $item->goods->id; ?></th>
                        <th><?= Html::a($item->goods->name, ['/goods/view', 'id' => $item->goods->id]); ?></th>
                        <th><?= $item->count; ?> <?= $Units[$item->goods->unit]['name']; ?></th>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>
