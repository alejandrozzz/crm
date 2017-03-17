<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Request;
use backend\models\__model_name__;
use backend\models\Module;
use backend\models\ModuleFields;

class DupaController extends Controller
{
    public $show_action = true;
    
    /**
     * Display a listing of the __module_name__.
     *
     * @return mixed
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
        $id = (int)Yii::$app->request->get('id');
        $module = Module::get($id);


        //if(Module::hasAccess($module->id)) {
        return $this->render('index', [
            'show_actions' => $this->show_action,
            'listing_cols' => Module::getListingColumns($id),
            'module' => $module
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
    public function actionCreate()
    {
        //
    }
    

    public function actionStore()
    {
        //if(Module::hasAccess("__module_name__", "create")) {
            
          //  $rules = Module::validateRules("__module_name__", $request);
            
            //$validator = Validator::make($request->all(), $rules);
            
            //if($validator->fails()) {
             //   return redirect()->back()->withErrors($validator)->withInput();
            //}
            //Yii::$app->db->createCommand()->insert();
           //
        var_dump(Yii::$app->request->post('Module')['name']);
            //////
        $insert_id = Module::insertModule(Yii::$app->request->post('Module')['name'], Yii::$app->request->post('Module'));
//            $model = new Module();

  //      $model->attributes=Yii::$app->request->post('Module');
    //    if (!$model->validate())
      //  {
        //    $model->errors;
        //}
        //$model->save();

            //////
            //$insert_id = $model->id;
        return $this->redirect(['dupa/show/', 'id' => $insert_id]);
     //       return redirect()->route(config('laraadmin.adminRoute') . '.__route_resource__.index');
            
       // } else {
         //   return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }

    public function actionShow()
    {

            $id = (int)Yii::$app->request->get('id');
            echo $id;

            $__singular_var__ = Module::find()->where(['id' => $id])->one();

            if(isset($__singular_var__->id)) {

                $module = Module::getModule($id);
                $module->row = $__singular_var__;

                return $this->render('show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding",
                    '__singular_var__' => $__singular_var__
                ]);
            }

    }
}