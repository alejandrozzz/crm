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
use backend\models\Uuu;

/**
 * Site controller
 */
class UuuController extends Controller
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
        $module = Module::getModule('Uuu');
        
        //if(Module::hasAccess($module->id)) {
            return $this->render('index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Uuu'),
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
        //if(Module::hasAccess("Uuu", "create")) {
            
            //$rules = Module::validateRules("Uuu", $request);
            
            //$validator = Validator::make($request->all(), $rules);
            
            //if($validator->fails()) {
             //   return redirect()->back()->withErrors($validator)->withInput();
           // }
            
            $insert_id = Module::insertModule("Uuu", Yii::$app->request->post());
            
            //return redirect()->route(config('laraadmin.adminRoute') . '.uuu.index');
            return $this->redirect(['show',
				'id' => $insert_id
			]);
        
    }

    public function actionShow()
    {
        //if(Module::hasAccess("Uuu", "view")) {
            
            $uuu = Uuu::find()->where(['id' => Yii::$app->request->get('id')])->one();
            if(isset($uuu->id)) {
                $module = Module::getModule('Uuu');
                $module->row = $uuu;
                
                return $this->render('show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding",
					'uuu' => $uuu
                ]);
            }
       // } else {
         //   return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
    

    public function actionEdit()
    {
        //if(Module::hasAccess("Uuu", "edit")) {
            $uuu = Uuu::find()->where(['id' => Yii::$app->request->get('id')])->one();
            if(isset($uuu->id)) {
                $module = Module::getModule('Uuu');
                
                $module->row = $uuu;
                
                return $this->render('edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
					'uuu' => $uuu
                ]);
            } 
    }
    

    public function actionUpdate()
    {
        
            $insert_id = Module::updateRow("Uuu", Yii::$app->request->post(), Yii::$app->request->post('id'));
            
           return $this->redirect(['index',
				'id' => $insert_id
			]);
    }
    

    public function actionDestroy()
    {
        /*if(Module::hasAccess("Uuu", "delete")) {
            Uuu::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.uuu.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }*/
    }

    public function actionDtajax()
    {
        $module = Module::getModule('Uuu');
        $listing_cols = Module::getListingColumns('Uuu');
        
        $values = Uuu::find()->select($listing_cols)->where(['IS', 'deleted_at', null])->asArray()->all();
		
        
        $fields_popup = ModuleFields::getModuleFields('Uuu');
        var_dump($fields_popup);
		//die();
        for($i = 0; $i < count($values); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if(($fields_popup[$col] != null) && substr((string)$fields_popup[$col]['popup_vals'], 0, strlen("@")) === "@") {
                    $values[$i][$j] = 34;
                }
                if($col == $module->view_col) {
                    $values[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/uuu/' . $values[$i][0]) . '">' . $values[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                //if(Module::hasAccess("Uuu", "edit")) {
                    $output .= '<a href="' . Yii::$app->urlManager->createUrl(['dupa/Uuu/edit', 'id' => $values[$i]]) . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                //}
                
                //if(Module::hasAccess("Uuu", "delete")) {
                    //$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.uuu.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
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
