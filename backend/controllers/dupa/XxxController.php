<?php
namespace backend\controllers\dupa;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\Module;
use backend\models\ModuleFields;
use backend\models\ModuleFieldTypes;
use backend\helpers\DupaHelper;
use backend\CodeGenerator;
use yii\helpers\Json;
use backend\models\Xxx;

/**
 * Site controller
 */
class XxxController extends Controller
{
	public $show_action = true;
    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['login', 'error'],
//                        'allow' => true,
//                    ],
//                    [
//                        'actions' => ['logout', 'index'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $module = Module::getModule('Xxx');
        
        //if(Module::hasAccess($module->id)) {
            return $this->render('index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Xxx'),
                'module' => get_object_vars($module)
            ]);
        //} else {
        //    return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    

    public function actionCreate()
    {
        //
    }

    public function actionStore()
    {
        //if(Module::hasAccess("Xxx", "create")) {
            
            //$rules = Module::validateRules("Xxx", $request);
            
            //$validator = Validator::make($request->all(), $rules);
            
            //if($validator->fails()) {
             //   return redirect()->back()->withErrors($validator)->withInput();
           // }
            
            $insert_id = Module::insertModule("Xxx", Yii::$app->request->post());
            
            //return redirect()->route(config('laraadmin.adminRoute') . '.xxx.index');
            return $this->redirect(['show',
				'id' => $insert_id
			]);
        
    }

    public function actionShow()
    {
        //if(Module::hasAccess("Xxx", "view")) {
            
            $xxx = Xxx::find()->where(['id' => Yii::$app->request->get('id')])->one();
            if(isset($xxx->id)) {
                $module = Module::getModule('Xxx');
                $module->row = $xxx;
                
                return $this->render('show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding",
					'xxx' => $xxx
                ]);
            }
       // } else {
         //   return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    

    public function actionEdit()
    {
        //if(Module::hasAccess("Xxx", "edit")) {
            $xxx = Xxx::find()->where(['id' => Yii::$app->request->get('id')])->one();
            if(isset($xxx->id)) {
                $module = Module::getModule('Xxx');
                
                $module->row = $xxx;
                
                return $this->render('edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
					'xxx' => $xxx
                ]);
            } 
    }
    

    public function actionUpdate()
    {
        
            $insert_id = Module::updateRow("Xxx", Yii::$app->request->post(), Yii::$app->request->post('id'));
            
           return $this->redirect(['index',
				'id' => $insert_id
			]);
    }
    

    public function actionDestroy()
    {
        /*if(Module::hasAccess("Xxx", "delete")) {
            Xxx::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.xxx.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }*/
    }

    public function actionDtajax()
    {
        $module = Module::getModule('Xxx');
        $listing_cols = Module::getListingColumns('Xxx');
        
        
		$values = xxx::find()->select($listing_cols)->where(['IS', 'deleted_at', null])->asArray()->all();
		
        
        $fields_popup = ModuleFields::getModuleFields('Xxx');
        var_dump($fields_popup);
				die();
        for($i = 0; $i < count($values); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
				
                if($fields_popup[$col] != null && substr($fields_popup[$col]['popup_vals'], 0, strlen("@")) === "@") {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/xxx/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                //if(Module::hasAccess("Xxx", "edit")) {
                    $output .= '<a href="' .Yii::$app->urlManager->createUrl(['dupa/xxx/edit', 'id' => $values[$i]]) . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                //}
                
                //if(Module::hasAccess("Xxx", "delete")) {
                    //$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.xxx.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
                    //$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                    //$output .= Form::close();
                //}
                $data->data[$i][] = (string)$output;
            }
        }
		return Json::encode([
            'data' => $data
        ]);
    }
}
