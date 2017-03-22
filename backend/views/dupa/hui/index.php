<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use backend\models\Module;
use backend\controllers\Dupa;
use yii\helpers\Url;
use backend\models\Hui;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;

$this->title = 'DupaCRM';
?>
<div class="site-index">

    <?php $listing_cols[] =[ 'class' => ActionColumn::className(),
        'buttons' => [
            'view' => function ($url, $model, $key) {
                $customurl=Yii::$app->getUrlManager()->createUrl(['/dupa/hui/edit','id'=>$model['id']]);
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $customurl);
            },
            'update' => function ($url, $model, $key) {
                return '';
            },
            'delete' => function ($url, $model, $key) {
                return '';
            }
        ]
    ];


    ?>
    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => Hui::find(),
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
			<?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/hui/store'], 'method' => 'post',
                'options'                => [
                    'id'      => 'hui-add-form'
					
                 ]]); ?>
			<div class="modal-body">
				<div class="box-body">
					
					<input type="text" name="colname" value="a">
					
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
<button class="btn btn-success btn-sm pull-right add_new_module_btn">Add Hui </button>
	
</div>
<script>
	$(function(){
		$('.add_new_module_btn').click('click', function(){
			$("#AddModal").modal('show');
		})
	})
</script>
