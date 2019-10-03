<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Invoices */
/* @var $form yii\widgets\ActiveForm */
/* @var $Places array */
/* @var $Stocks array */

$StocksArray = ArrayHelper::map($Stocks, 'id', 'name');
array_unshift($StocksArray, "Не со склада");

$PlacesArray = ArrayHelper::map($Places, 'id', 'name');
?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'row']]); ?>

<?php if ($model->isNewRecord) { ?>
    <div class="col-xs-12">

    <?php if (Yii::$app->user->identity->role == 'admin') { ?>
            <?= $form->field($model, 'places_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map($Places, 'id', 'name'),
                'pluginEvents' => [
                    "select2:select" => "function(e) {
	            var data = e.params.data;
                var thisID = data.id;
            }"]
            ]); ?>
    <?php } else { ?>
            <h3><?php echo $PlacesArray[Yii::$app->user->identity->places_id]; ?></h3>
    <?php } ?>
    </div>

    <div class="col-xs-12">
        <?= $form->field($model, 'stocks_id')->widget(Select2::classname(), [
            'data' => $StocksArray,
            'pluginEvents' => [
                "select2:select" => "function(e) {
	            var data = e.params.data;
                var thisID = data.id;
            }"]
        ]); ?>
    </div>

<?php } else { ?>
    <div class="col-xs-12">
        Заведение: <p><b><?= $model->places->name; ?></b></p>
        Склад: <p><b><?= $model->stocks->name; ?></b></p>
    </div>
<?php } ?>

    <div class="col-xs-12">
        <?= $form->field($model, 'name')->textInput() ?>
    </div>

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