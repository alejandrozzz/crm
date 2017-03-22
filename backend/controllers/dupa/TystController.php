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
use backend\models\Tyst;

/**
 * Site controller
 */
class TystController extends Controller
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
        $module = Module::getModule('Tyst');
        
        //if(Module::hasAccess($module->id)) {
            return $this->render('index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Tyst'),
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
        //if(Module::hasAccess("Tyst", "create")) {
            
            //$rules = Module::validateRules("Tyst", $request);
            
            //$validator = Validator::make($request->all(), $rules);
            
            //if($validator->fails()) {
             //   return redirect()->back()->withErrors($validator)->withInput();
           // }
            
            $insert_id = Module::insertModule("Tyst", Yii::$app->request->post());
            
            //return redirect()->route(config('laraadmin.adminRoute') . '.tyst.index');
            return $this->redirect(['show',
				'id' => $insert_id
			]);
        
    }

    public function actionShow()
    {
        //if(Module::hasAccess("Tyst", "view")) {
            
            $tyst = Tyst::find()->where(['id' => Yii::$app->request->get('id')])->one();
            if(isset($tyst->id)) {
                $module = Module::getModule('Tyst');
                $module->row = $tyst;
                
                return $this->render('show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding",
					'tyst' => $tyst
                ]);
            }
       // } else {
         //   return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    

    public function actionEdit()
    {
        //if(Module::hasAccess("Tyst", "edit")) {
            $tyst = Tyst::find()->where(['id' => Yii::$app->request->get('id')])->one();
            if(isset($tyst->id)) {
                $module = Module::getModule('Tyst');
                
                $module->row = $tyst;
                
                return $this->render('edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
					'tyst' => $tyst
                ]);
            } 
    }
    

    public function actionUpdate()
    {
        
            $insert_id = Module::updateRow("Tyst", Yii::$app->request->post(), Yii::$app->request->post('id'));
            
           return $this->redirect(['index',
				'id' => $insert_id
			]);
    }
    

    public function actionDestroy()
    {
        /*if(Module::hasAccess("Tyst", "delete")) {
            Tyst::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.tyst.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }*/
    }

    public function actionDtajax()
    {
        $module = Module::getModule('Tyst');
        $listing_cols = Module::getListingColumns('Tyst');
        
        $values = Tyst::find()->select($listing_cols)->where(['IS', 'deleted_at', null]);
		
        
        $fields_popup = ModuleFields::getModuleFields('Tyst');
        
        for($i = 0; $i < count($values); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]['popup_vals'], "@")) {
                    $values[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $values[$i][$j]);
                }
                if($col == $module->view_col) {
                    $values[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/tyst/' . $values[$i][0]) . '">' . $values[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                //if(Module::hasAccess("Tyst", "edit")) {
                    $output .= '<a href="' . Yii::$app->urlManager->createUrl(['dupa/Tyst/edit', 'id' => $values[$i]]) . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                //}
                
                //if(Module::hasAccess("Tyst", "delete")) {
                    //$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.tyst.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
