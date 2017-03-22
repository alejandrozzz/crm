<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use backend\models\Module;
use backend\controllers\Dupa;
use yii\helpers\Url;
use backend\models\Test10;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;

$this->title = 'Test10';
?>
<div class="right_col" role="main">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
                  <div class="x_title">
                    <h2>Test10 List</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table class="table">
                      <thead>
                        <tr>
							<th scope="row">Id</th>
							<?php foreach($listing_cols as $col) : ?>
								<th><?php echo $col ?></th>
							<?php endforeach;?>
							<th scope="row">Edit</th>
							<th scope="row">Delete</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php foreach (Test10::find()->all() as $m) : ?>
						  <tr>
                          
						  <td><?php echo $m->id ?></td>
						  <?php foreach($listing_cols as $col) : ?>
								<td><?php echo $m->$col ?></td>
							<?php endforeach;?>
							<td><?php echo Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->getUrlManager()->createUrl(['/dupa/test10/edit','id'=>$m->id])); ?></td>
							<td><?php echo Html::a('<span class="glyphicon glyphicon-remove"></span>', Yii::$app->getUrlManager()->createUrl(['/dupa/test10/destroy','id'=>$m->id])); ?></td>
                        </tr>
					  <?php endforeach;?>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
    <?php /*$listing_cols[] =[ 'class' => ActionColumn::className(),
        'buttons' => [
            'view' => function ($url, $model, $key) {
                $customurl=Yii::$app->getUrlManager()->createUrl(['/dupa/test10/edit','id'=>$model['id']]);
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

*/
    ?>
    <?php /*echo GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => Test10::find(),
        ]),
        'columns' => $listing_cols,

    ]); */?>
	
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
				<h4 class="modal-title" id="myModalLabel">Add New Test10</h4>
			</div>
			<?php $form = ActiveForm::begin([ 'enableClientValidation' => true, 'action'	=> ['dupa/test10/store'], 'method' => 'post',
                'options'                => [
                    'id'      => 'test10-add-form'
					
                 ]]); ?>
			<div class="modal-body">
				<div class="box-body">
					
					Do You really want to create a new instance of Test10 module?
					<div class="hide"><input type="text" name="colname" value="name"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
				<?php echo Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
			</div>
			<?php //Modal::end();
	  ActiveForm::end();?>
		</div>
	</div>
</div>

<button class="btn btn-success btn-sm pull-right add_new_module_btn">Add Test10 </button>
	</div>
</div>
<script>
	$(function(){
		$('.add_new_module_btn').click('click', function(){
			$("#AddModal").modal('show');
		})
	})
</script>
