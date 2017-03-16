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

        $module = Module::get('__module_name__');
        var_dump($module);
        die();
        //if(Module::hasAccess($module->id)) {
        return $this->render('dupa.__view_folder__.index', [
            'show_actions' => $this->show_action,
            'listing_cols' => Module::getListingColumns('__module_name__'),
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
    

    public function actionStore(Request $request)
    {
        //if(Module::hasAccess("__module_name__", "create")) {
            
          //  $rules = Module::validateRules("__module_name__", $request);
            
            //$validator = Validator::make($request->all(), $rules);
            
            //if($validator->fails()) {
             //   return redirect()->back()->withErrors($validator)->withInput();
            //}
            
            $insert_id = Module::insert("__module_name__", $request);
            
     //       return redirect()->route(config('laraadmin.adminRoute') . '.__route_resource__.index');
            
       // } else {
         //   return redirect(config('laraadmin.adminRoute') . "/");
        //}
    }
}