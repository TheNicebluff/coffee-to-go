<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\ChargeOffGoods */
/* @var $form yii\widgets\ActiveForm */
/* @var $Places array */
/* @var $Users array */
/* @var $Goods array */

$UsersArray = [];
foreach ($Users as $user) {
    $UsersArray[$user['id']] = $user['first_name'] . ' ' . $user['last_name'];
}

$PlacesArray = ArrayHelper::map($Places, 'id', 'name');
?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'row']]); ?>

<?php if (Yii::$app->user->identity->role == 'admin') { ?>
    <div class="col-xs-12">
        <?= $form->field($model, 'places_id')->widget(Select2::classname(), [
            'data' => $PlacesArray,
            'pluginEvents' => [
                "select2:select" => "function(e) {
            var data = e.params.data;
            var thisID = data.id;
        }"]
        ]); ?>
    </div>
<?php } else { ?>
    <div class="col-xs-12">
        <h3><?php echo $PlacesArray[Yii::$app->user->identity->places_id]; ?></h3>
    </div>
<?php } ?>

<div class="col-xs-12">
    <?= $form->field($model, 'goods_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($Goods, 'id', 'name'),
        'pluginEvents' => [
            "select2:select" => "function(e) {
            var data = e.params.data;
            var thisID = data.id;
        }"]
    ]); ?>
</div>

<?php if (Yii::$app->user->identity->role == 'admin') { ?>
    <div class="col-xs-12">
        <?= $form->field($model, 'users_id')->widget(Select2::classname(), [
            'data' => $UsersArray,
            'pluginEvents' => [
                "select2:select" => "function(e) {
            var data = e.params.data;
            var thisID = data.id;
        }"]]); ?>
    </div>
<?php } ?>

<div class="col-xs-12">
    <?= $form->field($model, 'count')->textInput() ?>
</div>

<div class="col-xs-12">
    <div class="form-group">
        <?= Html::submitButton((($model->isNewRecord) ? 'Добавить' : 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
