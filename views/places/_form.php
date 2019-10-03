<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Places */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([ 'options' => [  'class' => 'row' ]]); ?>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
</div>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
</div>

<div class="col-xs-12 col-lg-3">
    <?= $form->field($model, 'date_open')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => ''],
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
    ]); ?>
</div>

<div class="col-xs-12 col-lg-3">
    <?= $form->field($model, 'date_add')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => ''],
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
    ]); ?>
</div>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'phone')->textInput() ?>
</div>

<div class="col-xs-12">
    <div class="form-group">
        <?= Html::submitButton((($model->isNewRecord) ? 'Добавить' : 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
