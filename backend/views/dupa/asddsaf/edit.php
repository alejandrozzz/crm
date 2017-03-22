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
                <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/asddsaf/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => 'asddsaf-edit-form'

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $asddsaf['id'] ); ?>

                <?php
                \t\t\t\t\t $form->field( (object) $__singular_var__, aaaa)->textInput(["class"=>"form-control"]);\n
                ?>
                <br>
                <div class="form-group">
                    <?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<script>

</script>