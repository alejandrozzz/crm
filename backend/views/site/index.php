<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use backend\models\Module;
use backend\controllers\Dupa;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
	
	<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="dt_modules" class="table table-bordered">
		<thead>
		<tr class="success">
			<th>ID</th>
			<th>Name</th>
			<th>Table</th>
			<th>Items</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody>	

			<?php if (count($modules) > 0 ) : foreach ($modules as $module) : ?>
				<tr>
					<td><?php echo $module['id'] ?></td>
					<td><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/show','id'=> $module['id']]);?>"><?php echo $module['label'] ?></a></td>
					<td><?php echo $module['name_db'] ?></td>
					<td><?php echo Module::itemCount($module['name']) ?></td>
					<td>
						<a module_label="<?php echo $module['label'] ?>" module_icon="<?php echo $module['fa_icon'] ?>" module_id="<?php echo $module['id'] ?>" class="btn btn-primary btn-xs update_module" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>
						<a href="<?php echo Yii::$app->urlManager->createUrl(['/site/show','id'=> $module['id']]);?>#access" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-key"></i></a>
						<a href="<?php echo Yii::$app->urlManager->createUrl(['/site/show','id'=> $module['id']])?>#sort" class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-sort"></i></a>
						<a module_name="<?php echo $module['name'] ?>" module_id="<?php echo $module['id'] ?>" class="btn btn-danger btn-xs delete_module" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
			<?php endforeach; endif;?>
		</tbody>
		</table>
	</div>
</div>
    
	
		<?php 
		$model = new Module();
		
                
		//Modal::begin(['id' => 'AddModal', 'options' => ['class' => 'modal fade', 'role' => 'dialog'],
		//'header' => '<b>' . Yii::t('app', 'Create New Module') . '</b>',
		//'footer' => Html::submitButton('Send', ['class' => 'btn btn-success'])]);?>
		
	  
      
	  
	  
	  
	  <div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Module</h4>
			</div>
			<?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['site/store'], 'method' => 'post',
                'options'                => [
                    'id'      => 'module-add-form'
					
                 ]]); ?>
			<div class="modal-body">
				<div class="box-body">
					<div class="form-group">
						<?php echo $form->field($model, 'name')->textInput(['maxlength' => true])->label('Module Name: ') ?>
					</div>
					<div class="form-group">
						<?php echo $form->field($model, 'fa_icon')->textInput(['required' => 'required', 'placeholder' => 'FontAwesome Icon'])->label('Icon: ') ?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
			</div>
			<?php //Modal::end();
	  ActiveForm::end();?>
		</div>
	</div>
</div>
	  
	  
	  <div class="modal" id="module_update">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Update Module</h4>
			</div>
			<form id="module-update-form" role="form" action="/site/update" class="smart-form" novalidate="novalidate" method="post">
                
				<div class="modal-body">
					<div class="box-body">
						
						<?php if (isset($module)) echo  Html::hiddenInput('id', $module['id']); ?>
						<div class="form-group">
							<label for="name">Module Name :</label>
							<input type="text"  class="form-control module_label_edit" placeholder="Module Name" name="Module Name" value=""/>
						</div>
						<div class="form-group">
							<label for="icon">Icon</label>
							<div class="input-group">
								<input type="text" class="form-control module_icon_edit"  placeholder="FontAwesome Icon" name="icon"  value=""  data-rule-minlength="1" required>
								<span class="input-group-addon update-icon"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-success save_edit_module" data-dismiss="modal">Save</button>
				</div>
			</form>
		</div>
	</div>
	<!-- /.modal-dialog -->
</div>
<button class="btn btn-success btn-sm pull-right add_new_module_btn">Add New Module</button>
	
</div>
<script>
	$(function () {
		var csrfParam = $('meta[name="csrf-param"]').attr("content");
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
		var csrfObj = {};
		csrfObj[csrfParam]=csrfToken;
		$('.update_module').on("click", function () {
    	var module_id = $(this).attr('module_id');	 
		var module_label = $(this).attr('module_label');
		var module_icon = $(this).attr('module_icon');
		$(".module_label_edit").val(module_label);
		$(".module_icon_edit").val(module_icon);		
		$("#module_update").modal('show');
		$(".update-icon").html('<center><i class="fa '+module_icon+'"></i></center>');
		$('.save_edit_module').on("click", function () {
			var module_label = $(".module_label_edit").val();
			var module_icon = $(".module_icon_edit").val();
			$.ajax({
				url: "<?php echo Url::to(['/site/update'])?>",
				type:"POST",
				data : {'id':module_id,'label':module_label, 'icon':module_icon, '_csrf-backend' : csrfToken},
				success: function(data) {
					$("#module_update").modal('hide');
					location.reload();
					
				}
			});
		});
	});
		$('.add_new_module_btn').click('click', function(){
			$("#AddModal").modal('show');
		})
	})
</script>
