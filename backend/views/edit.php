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
                <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/__db_table_name__/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => '__singular_var__-edit-form'

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $__singular_var__['id'] ); ?>

                <?php
                echo 1111;
                $file_handle = fopen('CACHE_'.$__singular_var__['id'].'.php', 'w');
                fwrite($file_handle, __input_fields__);
                fclose($file_handle);
                include('CACHE_'.$__singular_var__['id'].'.php');
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