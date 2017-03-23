<?php
namespace frontend\controllers\dupa;

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
use backend\models\Employees;

/**
 * Site controller
 */
class EmployeesController extends Controller
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
		$this->bodyClass = 'nav-md footer_fixed';
        $module = Module::getModule('Employees');
        
        //if(Module::hasAccess($module->id)) {
            return $this->render('index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Employees'),
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
        //if(Module::hasAccess("Employees", "create")) {
            
            //$rules = Module::validateRules("Employees", $request);
            
            //$validator = Validator::make($request->all(), $rules);
            
            //if($validator->fails()) {
             //   return redirect()->back()->withErrors($validator)->withInput();
           // }
            
            $insert_id = Module::insertModule("Employees", Yii::$app->request->post());
            
            //return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
            return $this->redirect(['edit',
				'id' => $insert_id
			]);
        
    }

    public function actionShow()
    {
        //if(Module::hasAccess("Employees", "view")) {
            
            $employees = Employees::find()->where('id = ' . Yii::$app->request->get('id'))->one();
            if(isset($employees->id)) {
                $module = Module::getModule('Employees');
                $module->row = $employees;
                
                return $this->render('show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding",
					'employees' => $employees
                ]);
            }
       // } else {
         //   return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    

    public function actionEdit()
    {
        //if(Module::hasAccess("Employees", "edit")) {
            $employees = Employees::find()->where('id = ' . Yii::$app->request->get('id'))->one();
            if(isset($employees->id)) {
                $module = Module::getModule('Employees');
                
                $module->row = $employees;
                
                return $this->render('edit', [
                    'module' => $module,
					'listing_cols' => Module::getListingColumns('Employees'),
                    'view_col' => $module->view_col,
					'employees' => $employees
                ]);
            } 
    }
    

    public function actionUpdate()
    {
        
            $insert_id = Module::updateRow("Employees", Yii::$app->request->post(), Yii::$app->request->post('id'));
            
           return $this->redirect(['index',
				'id' => $insert_id
			]);
    }
    

    public function actionDestroy()
    {
        /*if(Module::hasAccess("Employees", "delete")) {*/
            Employees::find()->where(["id" => Yii::$app->request->get('id')])->one()->delete();
            
            // Redirecting to index() method
            return $this->redirect(['index',
				//'id' => $insert_id
			]);
    }

    public function actionDtajax()
    {
        /*$module = Module::getModule('Employees');
        $listing_cols = Module::getListingColumns('Employees');
        
        $values = DB::table('employees')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Employees');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/employees/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Employees", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/employees/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Employees", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.employees.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
