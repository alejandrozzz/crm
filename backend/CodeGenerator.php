<?php
namespace backend;
use Exception;
use Yii;
use yii\db\Schema;
use yii\db\Migration;
use backend\models\Module;
use backend\models\ModuleFieldTypes;
use backend\models\Menu;
use backend\helpers\DupaHelper;
use yii\helpers\Html;
use backend\Dupa;
class CodeGenerator
{
    /**
     * Generate Controller file
     *
     * @param $config config object storing the Module Names
     * @param null $comm command Object
     */
    public static function createController($config, $comm = null)
    {
        
        $templateDirectory = dirname(__DIR__) . '/backend/views';
        
        //DupaHelper::log("info", "Creating controller...", $comm);
        $md = file_get_contents($templateDirectory . "/controller.php");
        
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__model_name__", $config->modelName, $md);
        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__view_column__", $config->module->view_col, $md);
        
        // Listing columns
        $listing_cols = "";
        foreach($config->module->fields as $field) {
            $listing_cols .= "'" . $field['colname'] . "', ";
        }
        $listing_cols = trim($listing_cols, ", ");
        
        $md = str_replace("__listing_cols__", $listing_cols, $md);
        $md = str_replace("__view_folder__", $config->dbTableName, $md);
        $md = str_replace("__route_resource__", $config->dbTableName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
        
        file_put_contents(dirname(__DIR__).'/frontend/controllers/dupa/' . $config->controllerName . ".php", $md);
    }
    
    /**
     * Generate Model file
     *
     * @param $config config object storing the Module Names
     * @param null $comm command Object
     */
    public static function createModel($config, $comm = null)
    {		
        
        $templateDirectory = dirname(__DIR__) . '/backend/views';
        
        //LAHelper::log("info", "Creating model...", $comm);
        $md = file_get_contents($templateDirectory . "/model.php");
        
        $md = str_replace("__model_class_name__", $config->modelName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        
        file_put_contents(dirname(__DIR__).'/backend/Models/' . $config->modelName . ".php", $md);
    }
    
    /**
     * Generate Views for CRUD
     *
     * @param $config config object storing the Module Names
     * @param null $comm command Object
     */
    public static function createViews($config, $comm = null)
    {
        
        $templateDirectory = dirname(__DIR__) . '/backend/views';
        
        //LAHelper::log("info", "Creating views...", $comm);
        // Create Folder
        @mkdir(dirname(__DIR__)."/frontend/views/dupa/" . $config->dbTableName, 0777, true);
        
        // ============================ Listing / Index ============================
        $md = file_get_contents($templateDirectory . "/index.php");
        
        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
        $md = str_replace("__singular_cap_var__", $config->singularCapitalVar, $md);
        $md = str_replace("__module_name_2__", $config->moduleName2, $md);
        $md = str_replace("__small_module_name__", strtolower($config->moduleName), $md);

        // Listing columns
        $inputFields = "";
        foreach($config->module->fields as $field) {
			
            $inputFields .= "\t\t\t\t\t ".Html::textInput('colname', $field['colname'])."\n";
        }
        $inputFields = trim($inputFields);
        $md = str_replace("__input_fields__", $inputFields, $md);
        
        file_put_contents(dirname(__DIR__).'/frontend/views/dupa/' . $config->dbTableName . '/index.php', $md);
        
        // ============================ Edit ============================
        $md = file_get_contents($templateDirectory . "/edit.php");
        
        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
        $md = str_replace("__singular_cap_var__", $config->singularCapitalVar, $md);
        $md = str_replace("__module_name_2__", $config->moduleName2, $md);

        // Listing columns
        $inputFields = "";

        foreach($config->module->fields as $field) {

            //$inputFields .= '$form->field( (object) $__singular_var__, "'.$field["colname"].'")->textInput(["class"=>"form-control"]);';
			$inputFields .= DupaFormMaker::input($config->module,$field['colname']);
        }
        //$md = str_replace("__singular_var__", $config->singularVar, $inputFields);
        $inputFields = trim($inputFields);
        $md = str_replace("__input_fields__", $inputFields, $md);

        file_put_contents(dirname(__DIR__).'/frontend/views/dupa/' . $config->dbTableName . '/edit.php', $md);
        
        // ============================ Show ============================
        $md = file_get_contents($templateDirectory . "/show.php");
        
        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
        $md = str_replace("__singular_cap_var__", $config->singularCapitalVar, $md);
        $md = str_replace("__module_name_2__", $config->moduleName2, $md);
        
        // Listing columns
        $displayFields = "";
        foreach($config->module->fields as $field) {
            $displayFields .= "\t\t\t\t\t\t" . Html::textInput('colname', $field['colname'])."\n";
        }
        $displayFields = trim($displayFields);
        $md = str_replace("__display_fields__", $displayFields, $md);
        
        file_put_contents(dirname(__DIR__).'/frontend/views/dupa/' . $config->dbTableName . '/show.php', $md);
    }
    
    /**
     * Append module controller routes to admin_routes.php
     *
     * @param $config config object storing the Module Names
     * @param null $comm command Object
     */
    public static function appendRoutes($config, $comm = null)
    {
        
        /*$templateDirectory = __DIR__ . '/stubs';
        
        //LAHelper::log("info", "Appending routes...", $comm);
        if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
            $routesFile = base_path('routes/admin_routes.php');
        } else {
            $routesFile = app_path('Http/admin_routes.php');
        }
        
        $contents = file_get_contents($routesFile);
        $contents = str_replace('});', '', $contents);
        file_put_contents($routesFile, $contents);
        
        $md = file_get_contents($templateDirectory . "/routes.stub");
        
        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
        $md = str_replace("__singular_cap_var__", $config->singularCapitalVar, $md);
        
        file_put_contents($routesFile, $md, FILE_APPEND);*/
    }
    
    /**
     * Add Module to Menu
     *
     * @param $config config object storing the Module Names
     * @param null $comm command Object
     */
    public static function addMenu($config, $comm = null)
    {
        
        // $templateDirectory = __DIR__.'/stubs';
        
        //LAHelper::log("info", "Appending Menu...", $comm);
        if(count(Menu::find()->where(["url" => $config->dbTableName])->all()) == 0) {
            $menu = new Menu();
            $menu->name = $config->moduleName;
            $menu->url = $config->dbTableName;
            $menu->icon = "fa " . $config->fa_icon;
            $menu->type = 'module';
            $menu->parent = 0;
            $menu->save();
//            Menu::create([
//                "name" => $config->moduleName,
//                "url" => $config->dbTableName,
//                "icon" => "fa " . $config->fa_icon,
//                "type" => 'module',
//                "parent" => 0
//            ]);
        }
        
        // Old Method to add Menu
        // $menu = '<li><a href="{{ url(config("laraadmin.adminRoute") . '."'".'/'.$config->dbTableName."'".') }}"><i class="fa fa-cube"></i> <span>'.$config->moduleName.'</span></a></li>'."\n".'            <!-- LAMenus -->';
        // $md = file_get_contents(base_path('resources/views/la/layouts/partials/sidebar.blade.php'));
        // $md = str_replace("<!-- LAMenus -->", $menu, $md);
        // file_put_contents(base_path('resources/views/la/layouts/partials/sidebar.blade.php'), $md);
    }
    
    /**
     * Generate migration file
     *
     * CodeGenerator::generateMigration($table, $generateFromTable);
     *
     * @param $table table name
     * @param bool $generate true then create file from module info from DB
     * @param null $comm command Object
     * @throws Exception
     */
    public static function generateMigration($table, $generate = false, $comm = null)
    {
        //$filesystem = new Filesystem();
        
        if(substr($table, 0, strlen('create_')) === 'create_') {
            $tname = str_replace("create_", "", $table);
            $table = str_replace("_table", "", $tname);
        }
        
        $modelName = ucfirst($table);
        $tableP = strtolower($table).'s';
        $tableS = strtolower($table);
        $migrationName = 'create_' . $tableP . '_table';
        $migrationFileName = date("Y_m_d_His_") . $migrationName . ".php";
        $migrationClassName = ucfirst(ucwords($migrationName));
        $dbTableName = $tableP;
        $moduleName = ucfirst($table);
        
        //LAHelper::log("info", "Model:\t   " . $modelName, $comm);
        //LAHelper::log("info", "Module:\t   " . $moduleName, $comm);
        //LAHelper::log("info", "Table:\t   " . $dbTableName, $comm);
        //LAHelper::log("info", "Migration: " . $migrationName . "\n", $comm);
        
        // Reverse migration generation from table
        $generateData = "";
        $viewColumnName = "view_column_name e.g. name";
        
        // fa_icon
        $faIcon = "fa-cube";
        
        if($generate) {
            // check if table, module and module fields exists
            $module = Module::getModule($moduleName);
            if(isset($module)) {
               // LAHelper::log("info", "Module exists :\t   " . $moduleName, $comm);
                
                $viewColumnName = $module->view_col;
                $faIcon = $module->fa_icon;
                
                $ftypes = ModuleFieldTypes::getFTypes2();
                foreach($module->fields as $field) {
                    $ftype = $ftypes[$field['field_type']];
                    $unique = 0;
                    if($field['unique']) {
                        $unique = 1;
                    }
                    $dvalue = "";
                    if($field['defaultvalue'] != "") {
                        if(substr($field['defaultvalue'], 0, strlen("[")) === "[") {
                            $dvalue = $field['defaultvalue'];
                        } else {
                            $dvalue = '"' . $field['defaultvalue'] . '"';
                        }
                    } else {
                        $dvalue = '""';
                    }
                    $minlength = $field['minlength'];
                    $maxlength = $field['maxlength'];
                    $required = 0;
                    if($field['required']) {
                        $required = 1;
                    }
                    $listing_col = 0;
                    if($field['listing_col']) {
                        $listing_col = 1;
                    }
                    $values = "";
                    if($field['popup_vals'] != "") {
                        if(substr($field['popup_vals'], 0, strlen("[")) === "[") {
                            $values = $field['popup_vals'];
                        } else if (substr($field['popup_vals'], 0, strlen('"')) === '"'){
                            $values = $field['popup_vals'];
                        } else {
                            $values = '"' . $field['popup_vals'] . '"';
                        }
                    }
                    // $generateData .= '["'.$field['colname'].'", "'.$field['label'].'", "'.$ftype.'", '.$unique.', '.$dvalue.', '.$minlength.', '.$maxlength.', '.$required.''.$values.'],'."\n            ";
                    $generateData .= "[" .
                        "\n                \"colname\" => \"" . $field['colname'] . "\"," .
                        "\n                \"label\" => \"" . $field['label'] . "\"," .
                        "\n                \"field_type\" => \"" . $ftype . "\"," .
                        "\n                \"unique\" => " . $unique . "," .
                        "\n                \"defaultvalue\" => " . $dvalue . "," .
                        "\n                \"minlength\" => " . $minlength . "," .
                        "\n                \"maxlength\" => " . $maxlength . "," .
                        "\n                \"created_at\" => " . 0 . "," .
                        "\n                \"updated_at\" => " . 0 . "," .
                        "\n                \"deleted_at\" => " . 0 . "," .
                        "\n                \"required\" => " . $required . ",";
                    
                    if($values != "" && $values!='""""') {
                        $generateData .= "\n                \"listing_col\" => " . $listing_col . ",";
                        $generateData .= "\n                \"popup_vals\" => " . $values . ",";
                    } else {
                        $generateData .= "\n                \"listing_col\" => " . $listing_col . "";
                    }
                    $generateData .= "\n            ], ";
                }
                $generateData = trim($generateData, ", ");
                
                // Find existing migration file
                $mfiles = scandir(dirname(__DIR__).'/console/migrations/');
                // print_r($mfiles);
                $fileExists = false;
                $fileExistName = "";
                foreach($mfiles as $mfile) {
                    if(strpos($mfile, $migrationName) !== false) {
                        $fileExists = true;
                        $fileExistName = $mfile;
                    }
                }
                if($fileExists) {
                    //LAHelper::log("info", "Replacing old migration file: " . $fileExistName, $comm);
                    $migrationFileName = $fileExistName;
                } else {
                    // If migration not exists in migrations table
					
                    //if(!$m::find()->where([ 'migration' => $migrationName])->exists()) {
                        //$m = new Migration();
						//$m->version = str_replace(".php", "", $migrationFileName);
						//$m->save();
                    //}
                }
            } else {
                //LAHelper::log("error", "Module " . $moduleName . " doesn't exists; Cannot generate !!!", $comm);
            }
        }
        
        $templateDirectory = dirname(__DIR__) . '/backend/views';
        
        try {
            //LAHelper::log("line", "Creating migration...", $comm);
            $migrationData = file_get_contents($templateDirectory . "/migration.php");
            
            $migrationData = str_replace("__migration_class_name__", $migrationClassName, $migrationData);
            $migrationData = str_replace("__db_table_name__", $dbTableName, $migrationData);
            $migrationData = str_replace("__module_name__", $moduleName, $migrationData);
            $migrationData = str_replace("__model_name__", $modelName, $migrationData);
            $migrationData = str_replace("__view_column__", $viewColumnName, $migrationData);
            $migrationData = str_replace("__fa_icon__", $faIcon, $migrationData);
            $migrationData = str_replace("__generated__", $generateData, $migrationData);
            
            file_put_contents(dirname(__DIR__).'/console/migrations/' . $migrationFileName, $migrationData);
            // Load newly generated migration into environment. Needs in testing mode.
            require_once(dirname(__DIR__).'/console/migrations/'.$migrationFileName);
            
        } catch(Exception $e) {
            throw new Exception("Unable to generate migration for " . $table . " : " . $e->getMessage(), 1);
        }
        //LAHelper::log("info", "Migration done: " . $migrationFileName . "\n", $comm);
    }
    
    /**
     * Generate naming configuration for passed module required to generate
     * CRUDs, Model, Controller and Migration files
     *
     * $config = CodeGenerator::generateConfig($module_name);
     *
     * @param $module Module table in lowercase
     * @param $icon Module icon - FontAwesome
     * @return object Config Object with different names of Module
     * @throws Exception When Migration for this Module is not done
     */
    public static function generateConfig($module, $icon)
    {
        $config = array();
        $config = (object)$config;

        if(substr($module, 0, strlen('create_')) === 'create_') {
            $tname = str_replace("create_", "", $module);
            $module = str_replace("_table", "", $tname);
        }

        $config->modelName = ucfirst($module);
        $tableP = strtolower($module);
        $tableS = strtolower($module);
        $config->dbTableName = $tableP;
        $config->fa_icon = $icon;
        $config->moduleName = ucfirst($module);
        $config->moduleName2 = str_replace('_', ' ', ucfirst($module));
        $config->controllerName = ucfirst($module) . "Controller";
        $config->singularVar = strtolower($module);
        $config->singularCapitalVar = str_replace('_', ' ', ucfirst($module));
        
        $module = Module::getModule($config->moduleName);
        if(!isset($module->id)) {
            throw new Exception("Please run 'php artisan migrate' for 'create_" . $config->dbTableName . "_table' in order to create CRUD.\nOr check if any problem in Module Name '" . $config->moduleName . "'.", 1);
            return;
        }
        $config->module = $module;
        
        return $config;
    }
}