<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use backend\models\Module;
use backend\controllers\Dupa;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
	
	<?php foreach ($modules as $module) : ?>
		<?php echo ($module['id']); ?>
	<?php endforeach;?>
    
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add New Module</button>
	
		<?php 
		$model = new Module();
		$form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['site/store'], 'method' => 'post',
                'options'                => [
                    'id'      => '__singular_var__-add-form'
					
                 ]]);
                
		Modal::begin(['id' => 'AddModal',
		'header' => '<b>' . Yii::t('app', 'Create New Module') . '</b>',
		'footer' => Html::submitButton('Send', ['class' => 'btn btn-success'])]);?>
		
	  <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
      
	  <?php Modal::end();
	  ActiveForm::end();?>
</div>
