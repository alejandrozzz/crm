<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\DupaHelper;
use yii\db\Migration;

class Module extends ActiveRecord

{
    protected $table = 'modules';
    
    protected $fillable = [
        "name", "name_db", "label", "view_col", "model", "controller", "is_gen", "fa_icon"
    ];
    
    protected $hidden = [
    
    ];
	
	public static function generateBase($module_name, $icon)
    {
        
        $names = DupaHelper::generateModuleNames($module_name, $icon);
        
        // Check is Generated
        $is_gen = false;
        if(file_exists(base_path('backend/controllers/' . ($names->controller) . ".php"))) {
            if(($names->model == "User" || $names->model == "Role" || $names->model == "Permission") && file_exists(base_path('backend/models/' . ($names->model) . ".php"))) {
                $is_gen = true;
            } else if(file_exists(base_path('backend/models/' . ($names->model) . ".php"))) {
                $is_gen = true;
            }
        }
        $module = self::find()->where('name', $names->module)->one();
        if(!isset($module->id)) {
            $module = Module::create([
                'name' => $names->module,
                'label' => $names->label,
                'name_db' => $names->table,
                'view_col' => "",
                'model' => $names->model,
                'controller' => $names->controller,
                'fa_icon' => $names->fa_icon,
                'is_gen' => $is_gen,
            
            ]);
        }
        return $module->id;
    }
	
	public static function generate($module_name, $module_name_db, $view_col, $faIcon = "fa-cube", $fields)
    {
        
        $names = DupaHelper::generateModuleNames($module_name, $faIcon);
        $fields = Module::format_fields($module_name, $fields);
        
        if(substr_count($view_col, " ") || substr_count($view_col, ".")) {
            throw new Exception("Unable to generate migration for " . ($names->module) . " : Invalid view_column_name. 'This should be database friendly lowercase name.'", 1);
        } else if(!Module::validate_view_column($fields, $view_col)) {
            throw new Exception("Unable to generate migration for " . ($names->module) . " : view_column_name not found in field list.", 1);
        } else {
            // Check is Generated
            $is_gen = false;
            if(file_exists(base_path('backend/controllers/' . ($names->controller) . ".php"))) {
                if(($names->model == "User" || $names->model == "Role" || $names->model == "Permission") && file_exists(base_path('backend/models/' . ($names->model) . ".php"))) {
                    $is_gen = true;
                } else if(file_exists(base_path('backend/models/' . ($names->model) . ".php"))) {
                    $is_gen = true;
                }
            }
            
            // Create Module if not exists
            $module = Module::where('name', $names->module)->first();
            if(!isset($module->id)) {
                $module = Module::create([
                    'name' => $names->module,
                    'label' => $names->label,
                    'name_db' => $names->table,
                    'view_col' => $view_col,
                    'model' => $names->model,
                    'controller' => $names->controller,
                    'is_gen' => $is_gen,
                    'fa_icon' => $faIcon
                ]);
            }
            
            $ftypes = ModuleFieldTypes::getFTypes();
            //print_r($ftypes);
            //print_r($module);
            //print_r($fields);
            
            // Create Database Schema for table
            Migration::createTable($names->table, function (Blueprint $table) use ($fields, $module, $ftypes) {
                $table->increments('id');
                foreach($fields as $field) {
                    $mod = ModuleFields::find()->where('module', $module->id)->where('colname', $field->colname)->one();
                    if(!isset($mod->id)) {
                        if($field->field_type == "Multiselect" || $field->field_type == "Taginput") {
                            
                            if(is_string($field->defaultvalue) && starts_with($field->defaultvalue, "[")) {
                                $field->defaultvalue = json_decode($field->defaultvalue);
                            }
                            
                            if(is_string($field->defaultvalue) || is_int($field->defaultvalue)) {
                                $dvalue = json_encode([$field->defaultvalue]);
                            } else {
                                $dvalue = json_encode($field->defaultvalue);
                            }
                        } else {
                            $dvalue = $field->defaultvalue;
                            if(is_string($field->defaultvalue) || is_int($field->defaultvalue)) {
                                $dvalue = $field->defaultvalue;
                            } else if(is_array($field->defaultvalue) && is_object($field->defaultvalue)) {
                                $dvalue = json_encode($field->defaultvalue);
                            }
                        }
                        
                        $pvalues = $field->popup_vals;
                        if(is_array($field->popup_vals) || is_object($field->popup_vals)) {
                            $pvalues = json_encode($field->popup_vals);
                        }
                        
                        // Create Module field Metadata / Context
                        $field_obj = ModuleFields::create([
                            'module' => $module->id,
                            'colname' => $field->colname,
                            'label' => $field->label,
                            'field_type' => $ftypes[$field->field_type],
                            'unique' => $field->unique,
                            'defaultvalue' => $dvalue,
                            'minlength' => $field->minlength,
                            'maxlength' => $field->maxlength,
                            'required' => $field->required,
                            'listing_col' => $field->listing_col,
                            'popup_vals' => $pvalues
                        ]);
                        $field->id = $field_obj->id;
                        $field->module_obj = $module;
                    }
                    
                    // Create Module field schema in database
                    Module::create_field_schema($table, $field);
                }
                
                // $table->string('name');
                // $table->string('designation', 100);
                // $table->string('mobile', 20);
                // $table->string('mobile2', 20);
                // $table->string('email', 100)->unique();
                // $table->string('gender')->default('male');
                // $table->integer('dept')->unsigned();
                // $table->integer('role')->unsigned();
                // $table->string('city', 50);
                // $table->string('address', 1000);
                // $table->string('about', 1000);
                // $table->date('date_birth');
                // $table->date('date_hire');
                // $table->date('date_left');
                // $table->double('salary_cur');
                if($module->name_db == "users") {
                    $table->rememberToken();
                }
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }
	
	public static function create_field_schema($table, $field, $update = false, $isFieldTypeChange = false)
    {
        if(is_numeric($field->field_type)) {
            $ftypes = ModuleFieldTypes::getFTypes();
            $field->field_type = array_search($field->field_type, $ftypes);
        }
        if(!is_string($field->defaultvalue)) {
            $defval = json_encode($field->defaultvalue);
        } else {
            $defval = $field->defaultvalue;
        }
        // Log::debug('Module:create_field_schema ('.$update.') - '.$field->colname." - ".$field->field_type
        // ." - ".$defval." - ".$field->maxlength);
        
        // Create Field in Database for respective Field Type
        switch($field->field_type) {
            case 'Address':
                $var = null;
                if($field->maxlength == 0) {
                    if($update) {
                        $var = $table->text($field->colname)->change();
                    } else {
                        $var = $table->text($field->colname);
                    }
                } else {
                    if($update) {
                        $var = $table->string($field->colname, $field->maxlength)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname, $field->maxlength)->nullable();
                    }
                }
                if($field->defaultvalue != "") {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("");
                }
                break;
            case 'Checkbox':
                if($update) {
                    $var = $table->boolean($field->colname)->change();
                } else {
                    $var = $table->boolean($field->colname);
                }
                if($field->defaultvalue == "true" || $field->defaultvalue == "false" || $field->defaultvalue == true || $field->defaultvalue == false) {
                    if(is_string($field->defaultvalue)) {
                        if($field->defaultvalue == "true") {
                            $field->defaultvalue = true;
                        } else {
                            $field->defaultvalue = false;
                        }
                    }
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $field->defaultvalue = false;
                }
                break;
            case 'Currency':
                if($update) {
                    $var = $table->double($field->colname, 15, 2)->change();
                } else {
                    $var = $table->double($field->colname, 15, 2);
                }
                if($field->defaultvalue != "") {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("0.0");
                }
                break;
            case 'Date':
                if($update) {
                    $var = $table->date($field->colname)->nullable()->change();
                } else {
                    $var = $table->date($field->colname)->nullable();
                }
                
                if($field->defaultvalue == NULL || $field->defaultvalue == "" || $field->defaultvalue == "NULL") {
                    $var->default(NULL);
                } else if($field->defaultvalue == "now()") {
                    $var->default(NULL);
                } else if($field->required) {
                    $var->default("1970-01-01");
                } else {
                    $var->default($field->defaultvalue);
                }
                break;
            case 'Datetime':
                if($update) {
                    // Timestamp Edit Not working - http://stackoverflow.com/questions/34774628/how-do-i-make-doctrine-support-timestamp-columns
                    // Error Unknown column type "timestamp" requested. Any Doctrine type that you use has to be registered with \Doctrine\DBAL\Types\Type::addType()
                    // $var = $table->timestamp($field->colname)->change();
                } else {
                    $var = $table->timestamp($field->colname)->nullable()->nullableTimestamps();
                }
                // $table->timestamp('created_at')->useCurrent();
                if($field->defaultvalue == NULL || $field->defaultvalue == "" || $field->defaultvalue == "NULL") {
                    $var->default(NULL);
                } else if($field->defaultvalue == "now()") {
                    $var->default(DB::raw('CURRENT_TIMESTAMP'));
                } else if($field->required) {
                    $var->default("1970-01-01 01:01:01");
                } else {
                    $var->default($field->defaultvalue);
                }
                break;
            case 'Decimal':
                $var = null;
                if($update) {
                    $var = $table->decimal($field->colname, 15, 3)->change();
                } else {
                    $var = $table->decimal($field->colname, 15, 3);
                }
                if($field->defaultvalue != "") {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("0.0");
                }
                break;
            case 'Dropdown':
                if($field->popup_vals == "") {
                    if(is_int($field->defaultvalue)) {
                        if($update) {
                            $var = $table->integer($field->colname)->unsigned()->nullable()->change();
                        } else {
                            $var = $table->integer($field->colname)->unsigned()->nullable();
                        }
                        $var->default($field->defaultvalue);
                        break;
                    } else if(is_string($field->defaultvalue)) {
                        if($update) {
                            $var = $table->string($field->colname)->nullable()->change();
                        } else {
                            $var = $table->string($field->colname)->nullable();
                        }
                        $var->default($field->defaultvalue);
                        break;
                    }
                }
                $popup_vals = json_decode($field->popup_vals);
                if(starts_with($field->popup_vals, "@")) {
                    $foreign_table_name = str_replace("@", "", $field->popup_vals);
                    if($update) {
                        $var = $table->integer($field->colname)->nullable()->unsigned()->change();
                        if($field->defaultvalue == "" || $field->defaultvalue == "0") {
                            $var->default(NULL);
                        } else {
                            $var->default($field->defaultvalue);
                        }
                        $table->dropForeign($field->module_obj->name_db . "_" . $field->colname . "_foreign");
                        $table->foreign($field->colname)->references('id')->on($foreign_table_name)->onUpdate('cascade')->onDelete('cascade');
                    } else {
                        $var = $table->integer($field->colname)->nullable()->unsigned();
                        if($field->defaultvalue == "" || $field->defaultvalue == "0") {
                            $var->default(NULL);
                        } else {
                            $var->default($field->defaultvalue);
                        }
                        $table->foreign($field->colname)->references('id')->on($foreign_table_name)->onUpdate('cascade')->onDelete('cascade');
                    }
                } else if(is_array($popup_vals)) {
                    if($update) {
                        $var = $table->string($field->colname)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname)->nullable();
                    }
                    if($field->defaultvalue != "") {
                        $var->default($field->defaultvalue);
                    } else if($field->required) {
                        $var->default("");
                    }
                } else if(is_object($popup_vals)) {
                    // ############### Remaining
                    if($update) {
                        $var = $table->integer($field->colname)->nullable()->unsigned()->change();
                    } else {
                        $var = $table->integer($field->colname)->nullable()->unsigned();
                    }
                    // if(is_int($field->defaultvalue)) {
                    //     $var->default($field->defaultvalue);
                    //     break;
                    // }
                }
                break;
            case 'Email':
                $var = null;
                if($field->maxlength == 0) {
                    if($update) {
                        $var = $table->string($field->colname, 100)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname, 100)->nullable();
                    }
                } else {
                    if($update) {
                        $var = $table->string($field->colname, $field->maxlength)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname, $field->maxlength)->nullable();
                    }
                }
                if($field->defaultvalue != "") {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("");
                }
                break;
            case 'File':
                if($update) {
                    $var = $table->integer($field->colname)->change();
                } else {
                    $var = $table->integer($field->colname);
                }
                if($field->defaultvalue != "" && is_numeric($field->defaultvalue)) {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default(NULL);
                }
                break;
            case 'Files':
                if($update) {
                    $var = $table->string($field->colname, 256)->change();
                } else {
                    $var = $table->string($field->colname, 256);
                }
                if(is_string($field->defaultvalue) && starts_with($field->defaultvalue, "[")) {
                    $var->default($field->defaultvalue);
                } else if(is_array($field->defaultvalue)) {
                    $var->default(json_encode($field->defaultvalue));
                } else {
                    $var->default("[]");
                }
                break;
            case 'Float':
                if($update) {
                    $var = $table->float($field->colname)->change();
                } else {
                    $var = $table->float($field->colname);
                }
                if($field->defaultvalue != "") {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("0.0");
                }
                break;
            case 'HTML':
                if($update) {
                    $var = $table->string($field->colname, 10000)->nullable()->change();
                } else {
                    $var = $table->string($field->colname, 10000)->nullable();
                }
                if($field->defaultvalue != null) {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("");
                }
                break;
            case 'Image':
                if($update) {
                    $var = $table->integer($field->colname)->change();
                } else {
                    $var = $table->integer($field->colname);
                }
                if($field->defaultvalue != "" && is_numeric($field->defaultvalue)) {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default(NULL);
                } else {
                    $var->default(NULL);
                }
                break;
            case 'Integer':
                $var = null;
                if($update) {
                    $var = $table->integer($field->colname, false)->change();
                } else {
                    $var = $table->integer($field->colname, false);
                }
                if($field->defaultvalue != "") {
                    $var->default($field->defaultvalue);
                } else {
                    $var->default("0");
                }
                break;
            case 'Mobile':
                $var = null;
                if($field->maxlength == 0) {
                    if($update) {
                        $var = $table->string($field->colname)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname)->nullable();
                    }
                } else {
                    if($update) {
                        $var = $table->string($field->colname, $field->maxlength)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname, $field->maxlength)->nullable();
                    }
                }
                if($field->defaultvalue != "") {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("");
                }
                break;
            case 'Multiselect':
                if($update) {
                    $var = $table->string($field->colname, 256)->change();
                } else {
                    $var = $table->string($field->colname, 256);
                }
                if(is_array($field->defaultvalue)) {
                    $field->defaultvalue = json_encode($field->defaultvalue);
                    $var->default($field->defaultvalue);
                } else if(is_string($field->defaultvalue) && starts_with($field->defaultvalue, "[")) {
                    $var->default($field->defaultvalue);
                } else if($field->defaultvalue == "" || $field->defaultvalue == null) {
                    $var->default("[]");
                } else if(is_string($field->defaultvalue)) {
                    $field->defaultvalue = json_encode([$field->defaultvalue]);
                    $var->default($field->defaultvalue);
                } else if(is_int($field->defaultvalue)) {
                    $field->defaultvalue = json_encode([$field->defaultvalue]);
                    //echo "int: ".$field->defaultvalue;
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("[]");
                }
                break;
            case 'Name':
                $var = null;
                if($field->maxlength == 0) {
                    if($update) {
                        $var = $table->string($field->colname)->change();
                    } else {
                        $var = $table->string($field->colname);
                    }
                } else {
                    if($update) {
                        $var = $table->string($field->colname, $field->maxlength)->change();
                    } else {
                        $var = $table->string($field->colname, $field->maxlength);
                    }
                }
                if($field->defaultvalue != "") {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("");
                }
                break;
            case 'Password':
                $var = null;
                if($field->maxlength == 0) {
                    if($update) {
                        $var = $table->string($field->colname)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname)->nullable();
                    }
                } else {
                    if($update) {
                        $var = $table->string($field->colname, $field->maxlength)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname, $field->maxlength)->nullable();
                    }
                }
                if($field->defaultvalue != "") {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("");
                }
                break;
            case 'Radio':
                $var = null;
                if($field->popup_vals == "") {
                    if(is_int($field->defaultvalue)) {
                        if($update) {
                            $var = $table->integer($field->colname)->unsigned()->change();
                        } else {
                            $var = $table->integer($field->colname)->unsigned();
                        }
                        $var->default($field->defaultvalue);
                        break;
                    } else if(is_string($field->defaultvalue)) {
                        if($update) {
                            $var = $table->string($field->colname)->nullable()->change();
                        } else {
                            $var = $table->string($field->colname)->nullable();
                        }
                        $var->default($field->defaultvalue);
                        break;
                    }
                }
                if(is_string($field->popup_vals) && starts_with($field->popup_vals, "@")) {
                    if($update) {
                        $var = $table->integer($field->colname)->unsigned()->change();
                    } else {
                        $var = $table->integer($field->colname)->unsigned();
                    }
                    break;
                }
                $popup_vals = json_decode($field->popup_vals);
                if(is_array($popup_vals)) {
                    if($update) {
                        $var = $table->string($field->colname)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname)->nullable();
                    }
                    if($field->defaultvalue != "") {
                        $var->default($field->defaultvalue);
                    } else if($field->required) {
                        $var->default("");
                    }
                } else if(is_object($popup_vals)) {
                    // ############### Remaining
                    if($update) {
                        $var = $table->integer($field->colname)->unsigned()->change();
                    } else {
                        $var = $table->integer($field->colname)->unsigned();
                    }
                    // if(is_int($field->defaultvalue)) {
                    //     $var->default($field->defaultvalue);
                    //     break;
                    // }
                }
                break;
            case 'String':
                $var = null;
                if($field->maxlength == 0) {
                    if($update) {
                        $var = $table->string($field->colname)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname)->nullable();
                    }
                } else {
                    if($update) {
                        $var = $table->string($field->colname, $field->maxlength)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname, $field->maxlength)->nullable();
                    }
                }
                if($field->defaultvalue != null) {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("");
                }
                break;
            case 'Taginput':
                $var = null;
                if($update) {
                    $var = $table->string($field->colname, 1000)->nullable()->change();
                } else {
                    $var = $table->string($field->colname, 1000)->nullable();
                }
                if(is_string($field->defaultvalue) && starts_with($field->defaultvalue, "[")) {
                    $field->defaultvalue = json_decode($field->defaultvalue);
                }
                
                if(is_string($field->defaultvalue)) {
                    $field->defaultvalue = json_encode([$field->defaultvalue]);
                    //echo "string: ".$field->defaultvalue;
                    $var->default($field->defaultvalue);
                } else if(is_array($field->defaultvalue)) {
                    $field->defaultvalue = json_encode($field->defaultvalue);
                    //echo "array: ".$field->defaultvalue;
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("");
                }
                break;
            case 'Textarea':
                $var = null;
                if($field->maxlength == 0) {
                    if($update) {
                        $var = $table->text($field->colname)->change();
                    } else {
                        $var = $table->text($field->colname);
                    }
                } else {
                    if($update) {
                        $var = $table->string($field->colname, $field->maxlength)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname, $field->maxlength)->nullable();
                    }
                    if($field->defaultvalue != "") {
                        $var->default($field->defaultvalue);
                    } else if($field->required) {
                        $var->default("");
                    }
                }
                break;
            case 'TextField':
                $var = null;
                if($field->maxlength == 0) {
                    if($update) {
                        $var = $table->string($field->colname)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname)->nullable();
                    }
                } else {
                    if($update) {
                        $var = $table->string($field->colname, $field->maxlength)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname, $field->maxlength)->nullable();
                    }
                }
                if($field->defaultvalue != "") {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("");
                }
                break;
            case 'URL':
                $var = null;
                if($field->maxlength == 0) {
                    if($update) {
                        $var = $table->string($field->colname)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname)->nullable();
                    }
                } else {
                    if($update) {
                        $var = $table->string($field->colname, $field->maxlength)->nullable()->change();
                    } else {
                        $var = $table->string($field->colname, $field->maxlength)->nullable();
                    }
                }
                if($field->defaultvalue != "") {
                    $var->default($field->defaultvalue);
                } else if($field->required) {
                    $var->default("");
                }
                break;
        }
        
        // set column unique
        if($update) {
            if($isFieldTypeChange) {
                if($field->unique && $var != null && $field->maxlength < 256) {
                    $table->unique($field->colname);
                }
            }
        } else {
            if($field->unique && $var != null && $field->maxlength < 256) {
                $table->unique($field->colname);
            }
        }
    }
	public static function tableName(){
		return '{{modules}}';
	}
}