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
use backend\models\Adsfdsfd;

/**
 * Site controller
 */
class AdsfdsfdController extends Controller
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
        $module = Module::getModule('Adsfdsfd');
        
        //if(Module::hasAccess($module->id)) {
            return $this->render('index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Adsfdsfd'),
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
        //if(Module::hasAccess("Adsfdsfd", "create")) {
            
            //$rules = Module::validateRules("Adsfdsfd", $request);
            
            //$validator = Validator::make($request->all(), $rules);
            
            //if($validator->fails()) {
             //   return redirect()->back()->withErrors($validator)->withInput();
           // }
            
            $insert_id = Module::insertModule("Adsfdsfd", Yii::$app->request->post());
            
            //return redirect()->route(config('laraadmin.adminRoute') . '.adsfdsfd.index');
            return $this->redirect(['show',
				'id' => $insert_id
			]);
        
    }

    public function actionShow()
    {
        //if(Module::hasAccess("Adsfdsfd", "view")) {
            
            $adsfdsfd = Adsfdsfd::find()->where('id = ' . Yii::$app->request->get('id'))->one();
            if(isset($adsfdsfd->id)) {
                $module = Module::getModule('Adsfdsfd');
                $module->row = $adsfdsfd;
                
                return $this->render('show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding",
					'adsfdsfd' => $adsfdsfd
                ]);
            }
       // } else {
         //   return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    

    public function actionEdit()
    {
        //if(Module::hasAccess("Adsfdsfd", "edit")) {
            $adsfdsfd = Adsfdsfd::find()->where('id = ' . Yii::$app->request->get('id'))->one();
            if(isset($adsfdsfd->id)) {
                $module = Module::getModule('Adsfdsfd');
                
                $module->row = $adsfdsfd;
                
                return $this->render('edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
					'adsfdsfd' => $adsfdsfd
                ]);
            } 
    }
    

    public function actionUpdate()
    {
        
            $insert_id = Module::updateRow("Adsfdsfd", Yii::$app->request->post(), Yii::$app->request->post('id'));
            
           return $this->redirect(['index',
				'id' => $insert_id
			]);
    }
    

    public function actionDestroy()
    {
        /*if(Module::hasAccess("Adsfdsfd", "delete")) {
            Adsfdsfd::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.adsfdsfd.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }*/
    }

    public function actionDtajax()
    {
        /*$module = Module::getModule('Adsfdsfd');
        $listing_cols = Module::getListingColumns('Adsfdsfd');
        
        $values = DB::table('adsfdsfd')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Adsfdsfd');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/adsfdsfd/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Adsfdsfd", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/adsfdsfd/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Adsfdsfd", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.adsfdsfd.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
