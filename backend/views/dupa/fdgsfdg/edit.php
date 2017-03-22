<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="box">
    <div class="box-header">

    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/fdgsfdg/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => 'fdgsfdg-edit-form'

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $fdgsfdg['id'] ); ?>


                <input type="text" name="address" value="">

                <br>
                <div class="form-group">
                    <?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
