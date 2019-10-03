<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Expenses */
/* @var $form yii\widgets\ActiveForm */
/* @var $Places app\models\Places */
/* @var $ExpensesType app\models\ExpensesType */
/* @var $Places array */

$PlacesArray = ArrayHelper::map($Places, 'id', 'name');
?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'row']]); ?>

<div class="col-xs-12 col-lg-6">
    <?php if (Yii::$app->user->identity->role == 'admin') { ?>
        <?= $form->field($model, 'places_id')->widget(Select2::classname(), [
            'data' => $PlacesArray,
            'pluginEvents' => [
                "select2:select" => "function(e) {
                    var data = e.params.data;
                    var thisID = data.id;
                }",
            ]
        ]); ?>
    <?php } else { ?>
        <h3><?php echo $PlacesArray[Yii::$app->user->identity->places_id]; ?></h3>
    <?php } ?>
</div>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'expenses_type_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($ExpensesType, 'id', 'name'),
        'pluginEvents' => [
            "select2:select" => "function(e) {
	            var data = e.params.data;
                var thisID = data.id;
            }",
        ]
    ]); ?>
</div>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
</div>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'price')->textInput(['type' => 'number', 'step' => '0.01']); ?>
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
