<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\InvoicesStocks */
/* @var $form yii\widgets\ActiveForm */
/* @var $Stocks \app\models\Stocks */
?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'row']]); ?>

<div class="col-xs-12">
    <?= $form->field($model, 'name')->textInput() ?>
</div>

<?php if ($model->isNewRecord) { ?>
    <div class="col-xs-12">
        <?= $form->field($model, 'stocks_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($Stocks, 'id', 'name'),
            'pluginEvents' => [
                "select2:select" => "function(e) {
	            var data = e.params.data;
                var thisID = data.id;
            }" ]
        ]); ?>
    </div>
<?php } else { ?>
    <div class="col-xs-12">
        Склад: <p><b><?= $model->stocks->name; ?></b></p>
    </div>
<?php } ?>

<div class="col-xs-12">
    <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => ''],
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
    ]); ?>
</div>

<div class="col-xs-12">
    <div class="form-group">
        <?= Html::submitButton((($model->isNewRecord) ? 'Добавить' : 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

