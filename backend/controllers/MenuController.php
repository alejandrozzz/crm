<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\Module;
use backend\models\Menu;
use backend\models\ModuleFieldTypes;
use backend\helpers\DupaHelper;
use backend\CodeGenerator;
use yii\helpers\Json;

class MenuController extends Controller
{


    public function actionIndex()
    {
    $modules = Module::find()->all();

    // Send Menus with No Parent to Views
    $menuItems = Menu::find()->where(["parent" => 0])->orderBy('hierarchy', 'asc')->all();

    return $this->render('index', [
    'menus' => $menuItems,
    'modules' => $modules
    ]);
    }


    public function actionStore()
    {
        $post = Yii::$app->request->post();
        $name = $post['name'];
        $url = $post['url'];
        $icon = $post['icon'];
        $type = $post['type'];

        if($type == "module") {
            $module_id = $post['module_id'];
            $module = Module::find()->where(['id' => $module_id])->one();
            if(isset($module->id)) {
            $name = $module->name;
            $url = $module->name_db;
            $icon = $module->fa_icon;
        } else {
                return Json::encode([
                    "status" => "failure",
                    "message" => "Module does not exists"
                ]);
        }
        }
        $m = new Menu();
        $m->name = $name;
        $m->url = $url;
        $m->icon = $icon;
        $m->type = $type;
        $m->parent = 0;
        $m->save();
        if($type == "module") {
            return Json::encode([
                "status" => "success"
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }
//
//
//    public function update(Request $request, $id)
//    {
//    $name = Input::get('name');
//    $url = Input::get('url');
//    $icon = Input::get('icon');
//    $type = Input::get('type');
//
//    $menu = Menu::find($id);
//    $menu->name = $name;
//    $menu->url = $url;
//    $menu->icon = $icon;
//    $menu->save();
//
//    return redirect(config('laraadmin.adminRoute') . '/la_menus');
//    }
//
//
//    public function destroy($id)
//    {
//    Menu::find($id)->delete();
//
//    // Redirecting to index() method for Listing
//    return redirect()->route(config('laraadmin.adminRoute') . '.la_menus.index');
//    }
//
//
//    public function update_hierarchy()
//    {
//    $parents = Input::get('jsonData');
//    $parent_id = 0;
//
//    for($i = 0; $i < count($parents); $i++) {
//    $this->apply_hierarchy($parents[$i], $i + 1, $parent_id);
//    }
//
//    return $parents;
//    }
//
//
//    function apply_hierarchy($menuItem, $num, $parent_id)
//    {
//        // echo "apply_hierarchy: ".json_encode($menuItem)." - ".$num." - ".$parent_id."  <br><br>\n\n";
//        $menu = Menu::find($menuItem['id']);
//        $menu->parent = $parent_id;
//        $menu->hierarchy = $num;
//        $menu->save();
//
//        // apply hierarchy to children if exists
//        if(isset($menuItem['children'])) {
//            for($i = 0; $i < count($menuItem['children']); $i++) {
//            $this->apply_hierarchy($menuItem['children'][$i], $i + 1, $menuItem['id']);
//            }
//        }
//    }
}