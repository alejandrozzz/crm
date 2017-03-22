<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="right_col" role="main">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
                  <div class="x_title">
                    <h2>files List</h2>
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
                    
 <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/files/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => 'files-edit-form'

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $files['id'] ); ?>

                <div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-12' for='name'>name</label><input type="text" name="colname" value="name"></div>

                <br>
                <div class="form-group">
                    <?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
				</div>
	</div>
</div>
</div>
</div>