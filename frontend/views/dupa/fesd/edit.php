<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="right_col" role="main">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
                  <div class="x_title">
                    <h2>Fesd №<?=$fesd['id']?> Edit Form</h2>
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
                    
 <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/fesd/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => 'fesd-edit-form',
						'class'	=> 'form-horizontal form-label-left',

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $fesd['id'] ); ?>

                <div class="form-group"><label for="a">a :</label><textarea class="form-control" name="a" rows="3" cols="30" placeholder="Enter a" data-rule-minlength="0" data-rule-maxlength="256"></textarea></div><div class="form-group"><label for="b">b :</label><input type="hidden" value="false" name="b_hidden"><div class="form-control" data-rule-minlength="0"><label><input type="checkbox" name="b[]" value="0"> </label></div><div class="Switch Round On" style="vertical-align:top;margin-left:10px;"><div class="Toggle"></div></div></div><div class="form-group"><label for="d">d :</label><input type="text" class="form-control" name="d" value="" placeholder="Enter d" max="11" data-rule-currency="true" min="0"></div><div class="form-group"><label for="e">e :</label><div class='input-group date'><input type="text" class="form-control" name="e" value="" placeholder="Enter e" data-rule-minlength="0"><span class='input-group-addon input_dt'><span class='fa fa-calendar'></span></span><span class='input-group-addon null_date'><input class='cb_null_date' type='checkbox' name='null_date_e'  value='true'> Null ?</span></div></div><div class="form-group"><label for="f">f :</label><select class="form-control" name="f" size="4" rel="select2" data-placeholder="Enter f">
<option value="0" selected>None</option>
</select></div>

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