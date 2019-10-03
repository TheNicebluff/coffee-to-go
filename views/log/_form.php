<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Log */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'row']]); ?>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'users_id')->textInput() ?>
</div>

<div class="col-xs-12">
    <?= $form->field($model, 'date')->textInput() ?>
</div>

<div class="col-xs-12">
    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>
</div>

<div class="col-xs-12">
    <?= $form->field($model, 'post')->textarea(['rows' => 6]) ?>
</div>

<div class="col-xs-12">
    <div class="form-group">
        <?= Html::submitButton((($model->isNewRecord) ? 'Добавить' : 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
