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
use backend\models\Projects;

/**
 * Site controller
 */
class ProjectsController extends Controller
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
        $module = Module::getModule('Projects');
        
        //if(Module::hasAccess($module->id)) {
            return $this->render('index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Projects'),
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
        //if(Module::hasAccess("Projects", "create")) {
            
            //$rules = Module::validateRules("Projects", $request);
            
            //$validator = Validator::make($request->all(), $rules);
            
            //if($validator->fails()) {
             //   return redirect()->back()->withErrors($validator)->withInput();
           // }
            
            $insert_id = Module::insertModule("Projects", Yii::$app->request->post());
            
            //return redirect()->route(config('laraadmin.adminRoute') . '.projects.index');
            return $this->redirect(['show',
				'id' => $insert_id
			]);
        
    }

    public function actionShow()
    {
        //if(Module::hasAccess("Projects", "view")) {
            
            $projects = Projects::find()->where('id = ' . Yii::$app->request->get('id'))->one();
            if(isset($projects->id)) {
                $module = Module::getModule('Projects');
                $module->row = $projects;
                
                return $this->render('show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding",
					'projects' => $projects
                ]);
            }
       // } else {
         //   return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    

    public function actionEdit()
    {
        //if(Module::hasAccess("Projects", "edit")) {
            $projects = Projects::find()->where('id = ' . Yii::$app->request->get('id'))->one();
            if(isset($projects->id)) {
                $module = Module::getModule('Projects');
                
                $module->row = $projects;
                
                return $this->render('edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
					'projects' => $projects
                ]);
            } 
    }
    

    public function actionUpdate()
    {
        
            $insert_id = Module::updateRow("Projects", Yii::$app->request->post(), Yii::$app->request->post('id'));
            
           return $this->redirect(['index',
				'id' => $insert_id
			]);
    }
    

    public function actionDestroy()
    {
        /*if(Module::hasAccess("Projects", "delete")) {
            Projects::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.projects.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }*/
    }

    public function actionDtajax()
    {
        /*$module = Module::getModule('Projects');
        $listing_cols = Module::getListingColumns('Projects');
        
        $values = DB::table('projects')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Projects');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/projects/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Projects", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/projects/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Projects", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.projects.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
