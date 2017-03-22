<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
 <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/contacts/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => 'contacts-edit-form'

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $contacts['id'] ); ?>

                <input type="text" name="name" value="">

                <br>
                <div class="form-group">
                    <?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
    