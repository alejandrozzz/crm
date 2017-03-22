<?php
use backend\helpers\DupaHelper;
use yii\bootstrap\ActiveForm;
?>
<div class="box box-success menus">
    <!--<div class="box-header"></div>-->
    <div class="box-body">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-modules" data-toggle="tab">Modules</a></li>
                        <li><a href="#tab-custom-link" data-toggle="tab">Custom Links</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-modules">
                            <ul>
                                <?php foreach ($modules as $module) : ?>
                                <li><i class="fa <?=$module->fa_icon ?>"></i> <?=$module->label ?> <a module_id="<?=$module->id ?>" href="" class="addModuleMenu pull-right"><i class="fa fa-plus"></i></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="tab-pane" id="tab-custom-link">

                            <?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['menu/store'], 'method' => 'post',
                                'options'                => [
                                    'id'      => 'menu-custom-form'

                                ]]); ?>

                            <input type="hidden" name="type" value="custom">
                            <div class="form-group">
                                <label for="url" style="font-weight:normal;">URL</label>
                                <input class="form-control" placeholder="URL" name="url" type="text" value="http://" data-rule-minlength="1" required>
                            </div>
                            <div class="form-group">
                                <label for="name" style="font-weight:normal;">Label</label>
                                <input class="form-control" placeholder="Label" name="name" type="text" value=""  data-rule-minlength="1" required>
                            </div>
                            <div class="form-group">
                                <label for="icon" style="font-weight:normal;">Icon</label>
                                <div class="input-group">
                                    <input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="fa-cube"  data-rule-minlength="1" required>
                                    <span class="input-group-addon"></span>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary pull-right mr10" value="Add to menu">
                            <?php $form->end(); ?>
                        </div>
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
            </div>
            <div class="col-md-8 col-lg-8">
                <div class="dd" id="menu-nestable">
                    <ol class="dd-list">
                        <?php foreach ($menus as $menu) : ?>
                        <?php echo DupaHelper::print_menu_editor($menu); ?>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="EditModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Menu Item</h4>
            </div>
            {!! Form::open(['action' => ['\Dwij\Laraadmin\Controllers\MenuController@update', 1], 'id' => 'menu-edit-form']) !!}
            <input name="_method" type="hidden" value="PUT">
            <div class="modal-body">
                <div class="box-body">
                    <input type="hidden" name="type" value="custom">
                    <div class="form-group">
                        <label for="url" style="font-weight:normal;">URL</label>
                        <input class="form-control" placeholder="URL" name="url" type="text" value="http://" data-rule-minlength="1" required>
                    </div>
                    <div class="form-group">
                        <label for="name" style="font-weight:normal;">Label</label>
                        <input class="form-control" placeholder="Label" name="name" type="text" value=""  data-rule-minlength="1" required>
                    </div>
                    <div class="form-group">
                        <label for="icon" style="font-weight:normal;">Icon</label>
                        <div class="input-group">
                            <input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="fa-cube"  data-rule-minlength="1" required>
                            <span class="input-group-addon"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                {!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
