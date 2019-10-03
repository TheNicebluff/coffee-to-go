<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Places */
/* @var $Expenses \app\models\Expenses */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Заведения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$Units = Yii::$app->params['unit'];
$Currency = Yii::$app->params['currency'];

$ExpensesType = ArrayHelper::map($ExpensesType, 'id', 'name');

$attributes = [
    'id',
    'name',
    'address',
    'phone',
    'date_open',
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
        <h2 class="mb-3">Расходы</h2>
        <?php if ($model->expenses) { ?>
            <table class="table table-hover table_list">
                <thead class="">
                <tr>
                    <th width="60">ID</th>
                    <th>Тип</th>
                    <th>Название</th>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th width="60"></th>
                </tr>
                </thead>
                <tbody>
                <?
                $price = 0;
                foreach ($model->expenses as $item) {

                    $price += $item->price; ?>
                    <tr>
                        <td><?= $item->id; ?></td>
                        <td><?= $ExpensesType[$item->expenses_type_id]; ?></td>
                        <td><?= $item->name; ?></td>
                        <td><?= $item->date_add; ?></td>
                        <td><?= $item->price; ?> <?= $Currency; ?></td>
                        <td><?= Html::a(Html::tag('span', '', ['class' => "far fa-eye"]), ['/expenses/view', 'id' => $item->id], ['class' => 'btn_action btn btn-sm btn-primary']); ?></td>
                    </tr>
                <? } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="4">Итого</th>
                    <th><?= $price; ?> <?= $Currency; ?></th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
        <? } ?>

        <?php $form = ActiveForm::begin(['options' => ['class' => 'row']]); ?>

        <div class="col-xs-12 col-lg-5">
            <?= $form->field($Expenses, 'name')->textInput(['maxlength' => true]); ?>
        </div>

        <div class="col-xs-12 col-lg-5">
            <?= $form->field($Expenses, 'expenses_type_id')->widget(Select2::classname(), [
                'data' => $ExpensesType,
                'options' => [
                    'placeholder' => 'Select type',
                ],
                'pluginEvents' => [
                    "select2:select" => "function(e) {
	            var data = e.params.data;
                var thisID = data.id;
            }",]
            ])->label('Expenses Type'); ?>
        </div>

        <div class="col-xs-12 col-lg-2">
            <?= $form->field($Expenses, 'price')->textInput(['type' => 'number', 'step' => '0.01']); ?>
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <?= Html::submitButton((($Expenses->isNewRecord) ? 'Добавить' : 'Сохранить'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <div class="col-xs-12 col-lg-6">
        <h2 class="mb-3">Остаток на складе</h2>
        <?php if ($model->placesGoods) { ?>
            <table class="table table-hover table_list">
                <thead class="">
                <tr>
                    <th width="60">ID</th>
                    <th>Продукция</th>
                    <th width="120">Остаток</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($model->placesGoods as $item) {
                    if(!$item->goods) continue;
                    $thisUnit = $item->goods->unit;
                    ?>
                    <tr>
                        <th width="60"><?=$item->goods['id'];?></th>
                        <th><?= Html::a($item->goods['name'], ['/goods/view', 'id' => $item->goods['id']]); ?></th>
                        <th><?=$item->count;?> <?=$Units[$thisUnit]['name'];?></th>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>