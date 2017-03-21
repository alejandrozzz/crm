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
use backend\models\__model_name__;

/**
 * Site controller
 */
class __controller_class_name__ extends Controller
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
						__module_name__ => "/". __module_name__ . "/index"
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
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
        $module = Module::getModule('__module_name__');
        
        //if(Module::hasAccess($module->id)) {
            return $this->render('index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('__module_name__'),
                'module' => get_object_vars($module)
            ]);
        //} else {
        //    return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    
    /**
     * Show the form for creating a new __singular_var__.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created __singular_var__ in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        //if(Module::hasAccess("__module_name__", "create")) {
            
            //$rules = Module::validateRules("__module_name__", $request);
            
            //$validator = Validator::make($request->all(), $rules);
            
            //if($validator->fails()) {
             //   return redirect()->back()->withErrors($validator)->withInput();
           // }
            
            $insert_id = Module::insertModule("__module_name__", Yii::$app->request->post('Module'));
            
            //return redirect()->route(config('laraadmin.adminRoute') . '.__route_resource__.index');
            return $this->redirect(['show',
				'id' => $insert_id
			]);
        
    }
    
    /**
     * Display the specified __singular_var__.
     *
     * @param int $id __singular_var__ ID
     * @return mixed
     */
    public function show()
    {
        //if(Module::hasAccess("__module_name__", "view")) {
            
            $__singular_var__ = __model_name__::find()->where('id = ' . Yii::$app->request->get('id'))->one();
            if(isset($__singular_var__->id)) {
                $module = Module::getModule('__module_name__');
                $module->row = $__singular_var__;
                
                return $this->render('show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding",
					'__singular_var__' => $__singular_var__
                ]);
            }
       // } else {
         //   return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    
    /**
     * Show the form for editing the specified __singular_var__.
     *
     * @param int $id __singular_var__ ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit()
    {
        //if(Module::hasAccess("__module_name__", "edit")) {
            $__singular_var__ = __model_name__::find()->where('id = ' . Yii::$app->request->get('id'))->one();
            if(isset($__singular_var__->id)) {
                $module = Module::getModule('__module_name__');
                
                $module->row = $__singular_var__;
                
                return $this->render('edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
					'__singular_var__' => $__singular_var__
                ]);
            } 
    }
    
    /**
     * Update the specified __singular_var__ in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id __singular_var__ ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        
            $insert_id = Module::updateRow("__module_name__", Yii::$app->request->post('Module'), Yii::$app->request->post('Module')['id']);
            
           return $this->redirect(['index',
				'id' => $insert_id
			]);
    }
    
    /**
     * Remove the specified __singular_var__ from storage.
     *
     * @param int $id __singular_var__ ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        /*if(Module::hasAccess("__module_name__", "delete")) {
            __model_name__::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.__route_resource__.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }*/
    }
    
    /**
     * Server side Datatable fetch via Ajax
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dtajax()
    {
        /*$module = Module::getModule('__module_name__');
        $listing_cols = Module::getListingColumns('__module_name__');
        
        $values = DB::table('__db_table_name__')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('__module_name__');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/__route_resource__/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("__module_name__", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/__route_resource__/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("__module_name__", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.__route_resource__.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;*/
    }
}
