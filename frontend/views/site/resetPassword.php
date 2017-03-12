<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login_wrapper">
	<div class=" form ">
	<section class="login_content">
	<?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
            
<div>
                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'class' => 'form-control']) ?>
</div>
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'form-control', 'data-method'=>'post']) ?>
                </div>

            <?php ActiveForm::end(); ?>
			</section>
        </div>
 </div>
