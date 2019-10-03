<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['options' => ['class' => 'row', 'enctype' => 'multipart/form-data']]); ?>

<div class="col-xs-12">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
</div>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'image')->fileInput() ?>
</div>

<div class="col-xs-12">
    <?= $form->field($model, 'price_out')->textInput() ?>
</div>

<div class="col-xs-12">
    <div class="form-group">
        <?= Html::submitButton((($model->isNewRecord) ? 'Добавить' : 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
