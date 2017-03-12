<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

      
<div class="login_wrapper">
        <div id="register" class="form">
          <section class="login_content">
            <?php $form = ActiveForm::begin(); ?>
              <h1>Create Account</h1>
              <div>
			  <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control']) ?>
                
              </div>
              <div>
			  <?= $form->field($model, 'email') ?>
                <!--<input type="email" class="form-control" placeholder="Email" required="" />-->
              </div>
              <div>
			  <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control']) ?>
                <!--<input type="password" class="form-control" placeholder="Password" required="" />-->
              </div>
              <div>
				<?= Html::submitButton('Submit', ['class' => 'btn btn-default submit', 'name' => 'signup-button']) ?>
                
              </div>
<div class="separator">
                <p class="change_link">Already a member ? <?= Html::a('Log in', ['site/login'])?>
				
                </p>
				</div>
              <div class="clearfix"></div>

            
            <?php ActiveForm::end(); ?>
          </section>
        </div>
      </div>
    
