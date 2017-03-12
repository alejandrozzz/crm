<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login_wrapper">
	<div class=" form ">
	<section class="login_content">
	<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
	<h3>Please fill out your email.</h3>
	<div>
	<?= $form->field($model, 'email')->textInput(['autofocus' => true, 'class' => 'form-control']) ?>
	</div>
    <div class="form-group">
		<?= Html::submitButton('Send', ['class' => 'form-control']) ?>
	</div>   
	<?php ActiveForm::end(); ?>
	</section>
    </div>
</div>
