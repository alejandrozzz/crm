<?php
namespace backend\helpers;

use backend\models\module;
use backend\models\Menu;
use Yii;
class DupaHelper
{
    
    public static function generateModuleNames($module_name, $icon)
    {
        $array = array();
        $module_name = trim($module_name);
        $module_name = str_replace(" ", "_", $module_name);
        
        $array['module'] = ucfirst($module_name);
        $array['label'] = ucfirst($module_name);
        $array['table'] = strtolower($module_name);
        $array['model'] = ucfirst($module_name);
        $array['fa_icon'] = $icon;
        $array['controller'] = $array['module'] . "Controller";
        $array['singular_l'] = strtolower($module_name);
        $array['singular_c'] = ucfirst($module_name);
        
        return $array;
    }
	
	public static function getDBTables($remove_tables = [])
    {
		
        if(Yii::$app->db->driverName == "sqlite") {
            $tables_sqlite = DB::select('select * from sqlite_master where type="table"');
            $tables = array();
            foreach($tables_sqlite as $table) {
                if($table->tbl_name != 'sqlite_sequence') {
                    $tables[] = $table->tbl_name;
                }
            }
        } else if(Yii::$app->db->driverName == "pgsql") {
            $tables_pgsql = DB::select("SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema = 'public' ORDER BY table_name;");
            $tables = array();
            foreach($tables_pgsql as $table) {
                $tables[] = $table->table_name;
            }
        } else if(Yii::$app->db->driverName == "mysql") {
            $tables = Yii::$app->db->createCommand('SHOW TABLES')->queryAll();
        } else {
            $tables = Yii::$app->db->createCommand('SHOW TABLES')->queryAll();
        }
        
		//die();
        $tables_out = array();
        foreach($tables as $table) {
            $table = (Array)$table;
            $tables_out[] = array_values($table)[0];
        }
        if(in_array(-1, $remove_tables)) {
            $remove_tables2 = array();
        } else {
            $remove_tables2 = array(
                'backups',
                'la_configs',
                'la_menus',
                'migrations',
                'modules',
                'module_fields',
                'module_field_types',
                'password_resets',
                'permissions',
                'permission_role',
                'role_module',
                'role_module_fields',
                'role_user'
            );
        }
        $remove_tables = array_merge($remove_tables, $remove_tables2);
        $remove_tables = array_unique($remove_tables);
        $tables_out = array_diff($tables_out, $remove_tables);
        
        $tables_out2 = array();
        foreach($tables_out as $table) {
            $tables_out2[$table] = $table;
        }
        
        return $tables_out2;
    }
	
	public static function getModuleNames($remove_modules = [])
    {
        $modules = Module::find()->all();
        
        $modules_out = array();
        foreach($modules as $module) {
            $modules_out[] = $module->name;
        }
        $modules_out = array_diff($modules_out, $remove_modules);
        
        $modules_out2 = array();
        foreach($modules_out as $module) {
            $modules_out2[$module] = $module;
        }
        
        return $modules_out2;
    }
	
	public static function parseValues($value)
    {
        // return $value;
        $valueOut = "";
        if(strpos($value, '[') !== false) {
            $arr = json_decode($value);
            foreach($arr as $key) {
                $valueOut .= "<div class='label label-primary'>" . $key . "</div> ";
            }
        } else if(strpos($value, ',') !== false) {
            $arr = array_map('trim', explode(",", $value));
            foreach($arr as $key) {
                $valueOut .= "<div class='label label-primary'>" . $key . "</div> ";
            }
        } else if(strpos($value, '@') !== false) {
            $valueOut .= "<b data-toggle='tooltip' data-placement='top' title='From " . str_replace("@", "", $value) . " table' class='text-primary'>" . $value . "</b>";
        } else if($value == "") {
            $valueOut .= "";
        } else {
            $valueOut = "<div class='label label-primary'>" . $value . "</div> ";
        }
        return $valueOut;
    }
	
	public static function is_assoc_array(array $array)
    {
        // Keys of the array
        $keys = array_keys($array);
        
        // If the array keys of the keys match the keys, then the array must
        // not be associative (e.g. the keys array looked like {0:0, 1:1...}).
        return array_keys($keys) !== $keys;
    }

    public static function print_menu_editor($menu)
    {
        $editing = '';//\Collective\Html\FormFacade::open(['route' => [config('laraadmin.adminRoute') . '.la_menus.destroy', $menu->id], 'method' => 'delete', 'style' => 'display:inline']);
        $editing .= '<button class="btn btn-xs btn-danger pull-right"><i class="fa fa-times"></i></button>';
        //$editing .= \Collective\Html\FormFacade::close();
        if($menu->type != "module") {
            $info = (object)array();
            $info->id = $menu->id;
            $info->name = $menu->name;
            $info->url = $menu->url;
            $info->type = $menu->type;
            $info->icon = $menu->icon;

            $editing .= '<a class="editMenuBtn btn btn-xs btn-success pull-right" info=\'' . json_encode($info) . '\'><i class="fa fa-edit"></i></a>';
        }
        $str = '<li class="dd-item dd3-item" data-id="' . $menu->id . '">
			<div class="dd-handle dd3-handle"></div>
			<div class="dd3-content"><i class="fa ' . $menu->icon . '"></i> ' . $menu->name . ' ' . $editing . '</div>';

        $childrens = Menu::find()->where(["parent"=>$menu->id])->orderBy('hierarchy', 'asc')->all();

        if(count($childrens) > 0) {
            $str .= '<ol class="dd-list">';
            foreach($childrens as $children) {
                $str .= self::print_menu_editor($children);
            }
            $str .= '</ol>';
        }
        $str .= '</li>';
        return $str;
    }

    public static function print_menu($menu, $active = false)
    {
        $childrens = Menu::find()->where(["parent" => $menu->id])->orderBy('hierarchy', 'asc')->all();
        $treeview = "";
        $subviewSign = "";
        if(count($childrens)) {
            $treeview = " class=\"treeview\"";
            $subviewSign = '<i class="fa fa-angle-left pull-right"></i>';
        }
        $active_str = '';
        if($active) {
            $active_str = 'class="active"';
        }

        $str = '<li' . $treeview . ' ' . $active_str . '><a href="' . Yii::$app->urlManager->createUrl(["dupa/".$menu->url]) . '/index/"><i class="fa ' . $menu->icon . '"></i> <span>' . self::real_module_name($menu->name) . '</span> ' . $subviewSign . '</a>';

        if(count($childrens)) {
            $str .= '<ul class="treeview-menu">';
            foreach($childrens as $children) {
                $module = Module::get($children->url);
                //if(Module::hasAccess($module->id)) {
                    $str .= self::print_menu($children);
                //}
            }
            $str .= '</ul>';
        }
        $str .= '</li>';
        return $str;
    }

    public static function real_module_name($name)
    {
        $name = str_replace('_', ' ', $name);
        return $name;
    }
}