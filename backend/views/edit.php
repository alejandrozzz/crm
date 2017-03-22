<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="right_col" role="main">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
 <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/__db_table_name__/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => '__singular_var__-edit-form'

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $__singular_var__['id'] ); ?>

                __input_fields__

                <br>
                <div class="form-group">
                    <?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
</div>
</div>
</div>