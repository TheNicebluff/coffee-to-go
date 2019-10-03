<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([ 'enableAjaxValidation' => true, 'options' => [  'class' => 'row' ]]); ?>

<div class="col-xs-12 col-lg-4">
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
</div>

<div class="col-xs-12 col-lg-4">
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
</div>

<div class="col-xs-12 col-lg-4">
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
</div>

<div class="col-xs-12 col-lg-2">
    <?= $form->field($model, 'date_workstart')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => ''],
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
    ]); ?>
</div>

<div class="col-xs-12 col-lg-2">
    <?= $form->field($model, 'date_bd')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => ''],
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
    ]); ?>
</div>

<div class="col-xs-12 col-lg-4">
    <?= $form->field($model, 'role')->widget(Select2::classname(), [
        'data' => Yii::$app->params['role'],
        'hideSearch' => true,
        'options' => [
            'placeholder' => 'Select'
        ]
    ]); ?>
</div>

<div class="col-xs-12 col-lg-4">
    <?= $form->field($model, 'places_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($Places, 'id', 'name'),
        'options' => [
            'placeholder' => 'Select Places',
        ],
        'pluginEvents' => [
            "select2:select" => "function(e) {
	            var data = e.params.data;
                var thisID = data.id;
            }",
        ]
    ]); ?>
</div>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'password_new')->passwordInput(['maxlength' => true]); ?>
</div>

<div class="col-xs-12">
    <div class="form-group">
        <?= Html::submitButton((($model->isNewRecord) ? 'Добавить' : 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
