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
use backend\models\Test;

/**
 * Site controller
 */
class TestController extends Controller
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
						Test => "/". Test . "/index"
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
        $module = Module::getModule('Test');
        
        //if(Module::hasAccess($module->id)) {
            return $this->render('index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Test'),
                'module' => get_object_vars($module)
            ]);
        //} else {
        //    return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    
    /**
     * Show the form for creating a new test.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created test in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        //if(Module::hasAccess("Test", "create")) {
            
            //$rules = Module::validateRules("Test", $request);
            
            //$validator = Validator::make($request->all(), $rules);
            
            //if($validator->fails()) {
             //   return redirect()->back()->withErrors($validator)->withInput();
           // }
            
            $insert_id = Module::insertModule("Test", Yii::$app->request->post('Module'));
            
            //return redirect()->route(config('laraadmin.adminRoute') . '.test.index');
            return $this->redirect(['show',
				'id' => $insert_id
			]);
        
    }
    
    /**
     * Display the specified test.
     *
     * @param int $id test ID
     * @return mixed
     */
    public function show()
    {
        //if(Module::hasAccess("Test", "view")) {
            
            $test = Test::find()->where('id = ' . Yii::$app->request->get('id'))->one();
            if(isset($test->id)) {
                $module = Module::getModule('Test');
                $module->row = $test;
                
                return $this->render('show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding",
					'test' => $test
                ]);
            }
       // } else {
         //   return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    
    /**
     * Show the form for editing the specified test.
     *
     * @param int $id test ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit()
    {
        //if(Module::hasAccess("Test", "edit")) {
            $test = Test::find()->where('id = ' . Yii::$app->request->get('id'))->one();
            if(isset($test->id)) {
                $module = Module::getModule('Test');
                
                $module->row = $test;
                
                return $this->render('edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
					'test' => $test
                ]);
            } 
    }
    
    /**
     * Update the specified test in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id test ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        
            $insert_id = Module::updateRow("Test", Yii::$app->request->post('Module'), Yii::$app->request->post('Module')['id']);
            
           return $this->redirect(['index',
				'id' => $insert_id
			]);
    }
    
    /**
     * Remove the specified test from storage.
     *
     * @param int $id test ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        /*if(Module::hasAccess("Test", "delete")) {
            Test::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.test.index');
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
        /*$module = Module::getModule('Test');
        $listing_cols = Module::getListingColumns('Test');
        
        $values = DB::table('test')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Test');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/test/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Test", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/test/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Test", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.test.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
