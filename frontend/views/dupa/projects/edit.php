<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="right_col" role="main">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
 <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/projects/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => 'projects-edit-form',
						'form-horizontal form-label-left'

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $projects['id'] ); ?>

                <input type="text" name="name" value="">

                <br>
                <div class="form-group">
                    <?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
</div>
</div>
</div>