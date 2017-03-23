<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="right_col" role="main">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
                  <div class="x_title">
                    <h2>Employees №<?=$employees['id']?> Edit Form</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content" style="display: block;">
                    <br>
                    
 <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/employees/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => 'employees-edit-form',
						'class'	=> 'form-horizontal form-label-left',

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $employees['id'] ); ?>

                <div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-12' for='name'>name</label><div class='col-md-6 col-sm-6 col-xs-12'><input type="text" class="form-control col-md-7 col-xs-12" name="name" value=""></div></div><div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-12' for='user'>user</label><div class='col-md-6 col-sm-6 col-xs-12'><input type="text" class="form-control col-md-7 col-xs-12" name="user" value=""></div></div>

                <div class="ln_solid"></div>
                <div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						<?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
					</div>
                </div>
                <?php ActiveForm::end(); ?>
				</div>
	</div>
</div>
</div>
</div>
<?php 
	$tmp=[];
	foreach ($listing_cols as $col){
		$tmp[$col] = $module->row->attributes[$col];	
	}
	Yii::$app->view->registerJs("var values = '". json_encode($tmp) ."'",  \yii\web\View::POS_HEAD);
?>
<script>
	$(document).ready(function(){
		
		$.each(JSON.parse(values), function(index, value) {
			$('input[name="'+index+'"]').val(value);
		});
		
	})
</script>