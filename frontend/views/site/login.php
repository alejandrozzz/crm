<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login_wrapper">
	<div class=" form ">
	<section class="login_content">
	<?php $form = ActiveForm::begin(); ?>
	<h1>Login Form</h1>
	<div>
	<?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control']) ?>
	</div>
	<div>
	<?= $form->field($model, 'password')->passwordInput(['class' => 'form-control']) ?>
	</div>
	<div>
	<?= $form->field($model, 'rememberMe')->checkbox(['class' => '']) ?>
	</div>
	<div>
	<?= Html::submitButton('Log in', ['class' => 'btn btn-default submit', 'name' => 'login-button']) ?>
	
	<?= Html::a('Lost your password?', ['site/request-password-reset', 'class' => 'reset_pass']) ?>
	</div>
	<div class="separator">
                <p class="change_link">New to site? <?= Html::a('Create Account', ['site/register'], ['class' => 'to_register'])?>
                  
                </p>
				</div>
	<div class="clearfix"></div>
	<?php ActiveForm::end(); ?>
	</section>
	</div>
</div>
