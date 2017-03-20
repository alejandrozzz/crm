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
class FieldController extends Controller{
	
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
	
	public function actionStore()
    {
		
        $module_id =Yii::$app->request->post('module_id');
		
        $module = Module::find($module_id)->one();
        
        $field_id = ModuleFields::createField(Yii::$app->request->post());
		
        // Give Default Full Access to Super Admin
        //$role = \App\Role::where("name", "SUPER_ADMIN")->first();
        //Module::setDefaultFieldRoleAccess($field_id, $role->id, "full");
        
		return $this->redirect(['/site/show',
            'id' => (int)$module_id
        ]);
        
    }
}