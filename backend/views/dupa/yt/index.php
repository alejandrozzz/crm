<?php
$model_name = trim($module['model'],"'");
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use backend\models\Module;
use backend\controllers\Dupa;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
$this->title = 'DupaCRM';
?>
<div class="site-index">
	
	<div class="box box-success">
    <!--<div class="box-header"></div>-->
    <div class="box-body">
        <table id="example1" class="table table-bordered">
        <thead>
        <tr class="success">

            <?php foreach( $listing_cols as $col ) : ?>

            <th><?php echo $module['fields'][$col]['label']?></th>
            <?php endforeach; ?>
            <?php if($show_actions) : ?>
            <th>Actions</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
            
        </tbody>
        </table>
    </div>
</div>
    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => $model_name::find()->with('tags','authors'),
        ]),
        'columns' => $listing_cols,
    ]); ?>
	
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

			<div class="modal-body">
				<div class="box-body">
					
					<input type="text" name="colname" value="yt">
					 <input type="text" name="colname" value="yy">
					 <input type="text" name="colname" value="uu">
					
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
<button class="btn btn-success btn-sm pull-right add_new_module_btn">Add Yt </button>
	
</div>
<script>
	$(function(){
		$('.add_new_module_btn').click('click', function(){
			$("#AddModal").modal('show');
		})
	})
</script>
