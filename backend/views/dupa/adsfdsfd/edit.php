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
                <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/adsfdsfd/update'], 'method' => 'post',
                    'options'                => [
                        'id'      => 'adsfdsfd-edit-form'

                    ]]); ?>
                <?php echo Html::hiddenInput('id', $adsfdsfd['id'] ); ?>


                <input type="text" name="fgd" value="$row = (new \yii\db\Query())
                    -&gt;select($field[&#039;colname&#039;])
                -&gt;from($config-&gt;dbTableName)
                -&gt;where([&#039;id&#039; =&gt; $config-&gt;module-&gt;id])
                -&gt;one();">

                <br>
                <div class="form-group">
                    <?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
