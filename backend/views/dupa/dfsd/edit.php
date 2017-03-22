<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
 <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/dfsd/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => 'dfsd-edit-form'

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $dfsd['id'] ); ?>

                <input type="text" name="fdsadsd" value="">

                <br>
                <div class="form-group">
                    <?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
    