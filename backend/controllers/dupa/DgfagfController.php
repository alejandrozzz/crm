<?php
namespace backend\controllers;

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
use backend\models\Dgfagf;

/**
 * Site controller
 */
class DgfagfController extends Controller
{
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
		$modules = Module::find()->all();
        $tables = DupaHelper::getDBTables([]);
        
        return $this->render('index', [
            'modules' => $modules,
            'tables' => $tables
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionStore()
    {

        $module_id = Module::generateBase(Yii::$app->request->post('Module')['name'], Yii::$app->request->post('Module')['fa_icon']);
       
        return $this->redirect(['show',
            'id' => $module_id
        ]);
        //return redirect()->route(config('laraadmin.adminRoute') . '.modules.show', [$module_id]);
    }

    public function actionShow($id)
    {
		
        $ftypes = ModuleFieldTypes::getFTypes2();
        $module = Module::find()->where('id = ' . Yii::$app->request->get('id'))->one();
        $module = Module::getModule($module->name);
		
        $tables = DupaHelper::getDBTables([]);
        $modules = DupaHelper::getModuleNames([]);
		
        // Get Module Access for all roles
        //$roles = Module::getRoleAccess($id);

        return $this->render('show', [
            'no_header' => true,
            'no_padding' => "no-padding",
            'ftypes' => $ftypes,
            'tables' => $tables,
            'modules' => $modules,
            'roles' => [],
            'module' => get_object_vars($module)
        ]);
    }
	
	public function actionUpdate()
    {
		
		$post = Yii::$app->request->post();
        $module = Module::find()->where('id = ' . $post['id'])->one();
        if(isset($module->id)) {
            $module->label = ucfirst($post['label']);
            $module->fa_icon = $post['icon'];
            $module->save();
            /*
            $menu = Menu::where('url', strtolower($module->name))->where('type', 'module')->first();
            if(isset($menu->id)) {
                $menu->name = ucfirst($request->label);
                $menu->icon = $request->icon;
                $menu->save();
            }*/
        }
    }
	
	public function actionSet_view_col()
    {
		$get = Yii::$app->request->get();
        $module = Module::find()->where('id = ' . $get['id'])->one();
        $module->view_col = $get['column_name'];
        $module->save();
        
		return $this->redirect(['show',
            'id' => $get['id']
        ]);
    }
	
	public function actionGenerate_migr_crud()
    {
		$get = Yii::$app->request->get();
        $module = Module::find()->where(['id' => $get['module_id']])->one();
        $module = Module::getModule($module->name);
        
        // Generate Migration
        CodeGenerator::generateMigration($module->name_db, true);
        
        // Create Config for Code Generation
        $config = CodeGenerator::generateConfig($module->name, $module->fa_icon);
        
        // Generate CRUD
        CodeGenerator::createController($config);
        CodeGenerator::createModel($config);
        CodeGenerator::createViews($config);
        CodeGenerator::appendRoutes($config);
        CodeGenerator::addMenu($config);
        
        // Set Module Generated = True
        $module = Module::find()->where(['id' => $get['module_id']])->one();
        $module->is_gen = '1';
        $module->save();
        
        // Give Default Full Access to Super Admin
        //$role = Role::where("name", "SUPER_ADMIN")->first();
        //Module::setDefaultRoleAccess($module->id, $role->id, "full");
        
        return Json::encode([
            'status' => 'success'
        ]);
    }
}
