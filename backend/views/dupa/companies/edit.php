<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
 <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/companies/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => 'companies-edit-form'

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $companies['id'] ); ?>

                <input type="text" name="name" value="">
					 <input type="text" name="address" value="">
					 <input type="text" name="phone" value="">
					 <input type="text" name="mobile" value="">

                <br>
                <div class="form-group">
                    <?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
    