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
use backend\models\Test555;

/**
 * Site controller
 */
class Test555Controller extends Controller
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
        $module = Module::getModule('Test555');
        
        //if(Module::hasAccess($module->id)) {
            return $this->render('index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Test555'),
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
        //if(Module::hasAccess("Test555", "create")) {
            
            //$rules = Module::validateRules("Test555", $request);
            
            //$validator = Validator::make($request->all(), $rules);
            
            //if($validator->fails()) {
             //   return redirect()->back()->withErrors($validator)->withInput();
           // }
            
            $insert_id = Module::insertModule("Test555", Yii::$app->request->post());
            
            //return redirect()->route(config('laraadmin.adminRoute') . '.test555.index');
            return $this->redirect(['show',
				'id' => $insert_id
			]);
        
    }

    public function actionShow()
    {
        //if(Module::hasAccess("Test555", "view")) {
            
            $test555 = Test555::find()->where(['id' => Yii::$app->request->get('id')])->one();
            if(isset($test555->id)) {
                $module = Module::getModule('Test555');
                $module->row = $test555;
                
                return $this->render('show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding",
					'test555' => $test555
                ]);
            }
       // } else {
         //   return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    

    public function actionEdit()
    {
        //if(Module::hasAccess("Test555", "edit")) {
            $test555 = Test555::find()->where(['id' => Yii::$app->request->get('id')])->one();
            if(isset($test555->id)) {
                $module = Module::getModule('Test555');
                
                $module->row = $test555;
                
                return $this->render('edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
					'test555' => $test555
                ]);
            } 
    }
    

    public function actionUpdate()
    {
        
            $insert_id = Module::updateRow("Test555", Yii::$app->request->post(), Yii::$app->request->post('id'));
            
           return $this->redirect(['index',
				'id' => $insert_id
			]);
    }
    

    public function actionDestroy()
    {
        /*if(Module::hasAccess("Test555", "delete")) {
            Test555::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.test555.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }*/
    }

    public function actionDtajax()
    {
        $module = Module::getModule('Test555');
        $listing_cols = Module::getListingColumns('Test555');
        
        $values = Test555::find()->select($listing_cols)->where(['IS', 'deleted_at', null]);
		
        
        $fields_popup = ModuleFields::getModuleFields('Test555');
        
        for($i = 0; $i < count($values); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && substr($fields_popup[$col]['popup_vals'], 0, strlen("@")) === "@") {
                    $values[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $values[$i][$j]);
                }
                if($col == $module->view_col) {
                    $values[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/test555/' . $values[$i][0]) . '">' . $values[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                //if(Module::hasAccess("Test555", "edit")) {
                    $output .= '<a href="' . Yii::$app->urlManager->createUrl(['dupa/Test555/edit', 'id' => $values[$i]]) . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                //}
                
                //if(Module::hasAccess("Test555", "delete")) {
                    //$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.test555.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
                   // $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                   // $output .= Form::close();
                //}
                $values[$i][] = (string)$output;
            }
        }
        return Json::encode([
            'data' => $values
        ]);
    }
}
