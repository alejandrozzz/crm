<?php
namespace backend\helpers;

use backend\models\module;
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
}