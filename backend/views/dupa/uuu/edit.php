<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?> 
<div class="box">
    <div class="box-header">
        
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
				<?php 	
						$form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/uuu/update'], 'method' => 'put',
						'options'                => [
						'id'      => 'uuu-edit-form'
						]]);
				 ?>
				 <?php echo  Html::hiddenInput('id', $uuu->id); ?>
                    <input type="text" name="colname" value="gf">
                    <br>
                    <div class="form-group">
                      <?php echo Html::submitButton('Update', ['class' => 'btn btn-success']) ?>  <a href="<?php echo Yii::$app->urlManager->createUrl(['/dupa/uuu/']) ?>" class="btn btn-default pull-right">Cancel</a>
                    </div>
                <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>