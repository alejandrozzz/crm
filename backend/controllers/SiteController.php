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

/**
 * Site controller
 */
class SiteController extends Controller
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

        $module_id = Module::generateBase(Yii::$app->request->post('Module')['name'], '');
       
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
}
