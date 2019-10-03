<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Сброс пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-reset-password form-box">

    <h1 class="h4"><?= Html::encode($this->title) ?></h1>

    <?= Html::tag('p', 'Введите Ваш новый пароль.') ?>

    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

    <?= $form->field($model, 'password', ["template" => "{input}\n{error}"])->passwordInput(['autofocus' => true]) ?>

    <p><?= Html::submitButton('Сохранить', ['class' => 'btn btn-lg btn-primary btn-block']) ?></p>

    <?php ActiveForm::end(); ?>

</div>