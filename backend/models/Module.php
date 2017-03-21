<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Module extends ActiveRecord

{
    protected $table = 'modules';
    
    protected $fillable = [
        "name", "name_db", "label", "view_col", "model", "controller", "is_gen", "fa_icon"
    ];
    
    protected $hidden = [
    
    ];
    public function rules(){

        return [
            [['name'], 'string'],
            [['label'], 'string'],
            [['name_db'], 'string'],
            [['view_col'], 'string'],
            [['model'], 'string'],
            [['controller'], 'string'],
            [['fa_icon'], 'string'],
            [['is_gen'], 'integer'],
            [['deleted_at'], 'integer']

        ];
    }

	public static function generateBase($module_name, $icon)
    {
        
        $names = DupaHelper::generateModuleNames($module_name, $icon);
        ///var_dump($names);
        // Check is Generated
        $is_gen = false;
        if(file_exists(__DIR__.'backend/controllers/' . ($names['controller']) . ".php")) {
            if(($names['model'] == "User" || $names['model'] == "Role" || $names['model'] == "Permission") && file_exists(__DIR__.'backend/models/' . ($names['model']) . ".php")) {
                $is_gen = true;
            } else if(file_exists(__DIR__.'backend/models/' . ($names['model']) . ".php")) {
                $is_gen = true;
            }
        }

        $module = self::find()->where("name like '%".$names['model']."%'")->one();

        if(empty($module)) {

            $module = new Module();
            //var_dump($module->attributes());
            $names['name'] = $names['module'];
            unset($names['module']);
            $names['name_db'] = $names['table'];
            unset($names['table']);
            $names['view_col'] = '';
            $names['is_gen'] = 0;
            $names['created_at'] = 0;
            $names['updated_at'] = 0;
            $names['deleted_at'] = 0;
            //var_dump($names);
            $module->attributes = $names;
            
//            $module->name = $names->module;
//            $module->label = $names->label;
//            $module->name_db = $names->table;
//            $module->view_col = "";
//            $module->model = $names->model;
//            $module->controller = $names->controller;
//            $module->fa_icon = $names->fa_icon;
//            $module->is_gen = $is_gen;

            $module->save();

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
            if(file_exists(dirname(__DIR__).'/backend/controllers/' . ($names->controller) . ".php")) {
                if(($names->model == "User" || $names->model == "Role" || $names->model == "Permission") && file_exists(base_path('backend/models/' . ($names->model) . ".php"))) {
                    $is_gen = true;
                } else if(dirname(__DIR__).'/backend/models/' . ($names->model) . ".php") {
                    $is_gen = true;
                }
            }
            
            // Create Module if not exists
            $module = Module::find()->where(['name' => $names->module])->one();
            if(!isset($module->id)) {
                $module = new Module();

                    $module->name = $names->module;
                    $module->label = $names->label;
                    $module->name_db = $names->table;
                    $module->view_col = $view_col;
                    $module->model = $names->model;
                    $module->controller = $names->controller;
                    $module->is_gen = $is_gen;
                    $module->fa_icon = $faIcon;
                    $module->save();
            }
            
            $ftypes = ModuleFieldTypes::getFTypes();
            //print_r($ftypes);
            //print_r($module);
            //print_r($fields);
            
            // Create Database Schema for table
            $m = new Migration();$m->createTable($names->table, $fields);
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

            $m = new Migration();

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
                    $field_obj = ModuleFields::createField([
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
                self::create_field_schema($m, $field);
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
                $m->createTable($module['name_db'], [
                    $fields
                ], $tableOptions);


            };
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
                        $var = $table->string($field->colname)->change();
                    } else {
                        $var = $table->string($field->colname);
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
	
	public static function getListingColumns($module_id_name, $isObjects = false)
    {
        $module = null;
        if(is_int($module_id_name)) {
            $module = self::get($module_id_name);
        } else {
            $module = self::find()->where(['name' => $module_id_name])->one();
        }
        $listing_cols = ModuleFields::find()->where(['module' => $module->id])->andWhere(['listing_col' => 1])->orderBy('sort', 'asc')->asArray()->all();
        
        if($isObjects) {
            $id_col = array('label' => 'id', 'colname' => 'id');
        } else {
            $id_col = 'id';
        }
        $listing_cols_temp = [];
        foreach($listing_cols as $col) {
            //if(Module::hasFieldAccess($module->id, $col['id'])) {
                if($isObjects) {
                    $listing_cols_temp[] = $col;
                } else {
                    $listing_cols_temp[] = $col['colname'];
                }
            //}
        }
        return $listing_cols_temp;
    }

    public static function getModule($module_name)
    {
        $module = null;
        if(is_int($module_name)) {
            $module = self::find()->where(['id' => $module_name])->asArray()->one();
        } else {
            $module = self::find()->where(['name' => $module_name, 'name_db' => $module_name])->asArray()->one();
        }

        // If Module is found in database also attach its field array to it.
        if(isset($module)) {
            $module = $module;
			
            $fields = ModuleFields::find()->where(['module'=>$module['id']])->orderBy('sort', 'asc')->asArray()->all();
			
            $fields2 = array();
            foreach($fields as $field) {
                $fields2[$field['colname']] = $field;
            }
            $module['fields'] = $fields2;
			
            return (object)$module;
        } else {
            return null;
        }
    }

    public static function insertModule($module_name, $request)
    {
        $module = Module::getModule($module_name);

        if(isset($module)) {

            $model_name = ucfirst($module_name);
            if($model_name == "User" || $model_name == "Role" || $model_name == "Permission") {
                $model = "backend\\" . ucfirst($module_name);
            } else {
                $model = "backend\\models\\" . ucfirst($module_name);
            }

            // Delete if unique rows available which are deleted
            $old_row = null;
            $uniqueFields = ModuleFields::find()->where(['module' => $module->id])->where(['unique' => '1'])->asArray()->all();
            foreach($uniqueFields as $field) {
                //Log::debug("insert: " . $module->name_db . " - " . $field['colname'] . " - " . $request->{$field['colname']});
                $old_row = $model::find()->where(['is not', 'deleted_at', null])->andWhere([$request['colname'] => $request['colname']])->one();
                if(isset($old_row->id)) {
                    //Log::debug("deleting: " . $module->name_db . " - " . $field['colname'] . " - " . $request->{$field['colname']});
                    $model::find()->where(['is not', 'deleted_at', null])->andWhere([$field['colname'] => $request[$field['colname']]])->one()->delete();
                }
            }

            $row = new $model;
            if(isset($old_row->id)) {
                // To keep old & new row id remain same
                $row->id = $old_row->id;
            }
            $row = self::processDBRow($module, $request, $row);
            $row->save();
            return $row->id;
        } else {
            return null;
        }
    }
	
	 public static function itemCount($module_name)
    {
        $module = Module::getModule($module_name);

        if(isset($module)) {
            $model_name = ucfirst($module_name);
            if($model_name == "User" || $model_name == "Role" || $model_name == "Permission") {
                if(file_exists(dirname(__DIR__).'/backend/' . $model_name . ".php")) {
                    $model = dirname(__DIR__)."\\backend\\" . $model_name;
                    return count($model::find()->all());
                } else {
                    return -1;
                }
            } else {
				
				//die();
                if(file_exists(dirname(__DIR__).'/models/' . $model_name . ".php")) {
                    $model = "backend\\models\\" . $model_name;
                    return count($model::find()->asArray()->all());
                } else {
                    return -1;
                }
            }
        } else {
            return -1;
        }
    }
	
	public static function format_fields($module_name, $fields)
    {
        $out = array();
        foreach($fields as $field) {
            // Check if field format is New
            if(DupaHelper::is_assoc_array($field)) {
                $obj = (object)$field;
                
                if(!isset($obj->colname)) {
                    throw new Exception("Migration " . $module_name . " -  Field does not have colname", 1);
                } else if(!isset($obj->label)) {
                    throw new Exception("Migration " . $module_name . " -  Field does not have label", 1);
                } else if(!isset($obj->field_type)) {
                    throw new Exception("Migration " . $module_name . " -  Field does not have field_type", 1);
                }
                if(!isset($obj->unique)) {
                    $obj->unique = 0;
                }
                if(!isset($obj->defaultvalue)) {
                    $obj->defaultvalue = '';
                }
                if(!isset($obj->minlength)) {
                    $obj->minlength = 0;
                }
                if(!isset($obj->maxlength)) {
                    $obj->maxlength = 0;
                } else {
                    // Because maxlength above 256 will not be supported by Unique
                    if($obj->unique) {
                        $obj->maxlength = 250;
                    } else {
                        $obj->maxlength = $obj->maxlength;
                    }
                }
                if(!isset($obj->required)) {
                    $obj->required = 0;
                }
                if(!isset($obj->listing_col)) {
                    $obj->listing_col = 1;
                } else {
                    if($obj->listing_col == true) {
                        $obj->listing_col = 1;
                    } else {
                        $obj->listing_col = 0;
                    }
                }
                
                if(!isset($obj->popup_vals)) {
                    $obj->popup_vals = "";
                } else {
                    if(is_array($obj->popup_vals)) {
                        $obj->popup_vals = json_encode($obj->popup_vals);
                    } else {
                        $obj->popup_vals = $obj->popup_vals;
                    }
                }
                // var_dump($obj);
                $out[] = $obj;
            } else {
                // Handle Old field format - Sequential Array
                $obj = (Object)array();
                $obj->colname = $field[0];
                $obj->label = $field[1];
                $obj->field_type = $field[2];
                
                if(!isset($field[3])) {
                    $obj->unique = 0;
                } else {
                    $obj->unique = $field[3];
                }
                if(!isset($field[4])) {
                    $obj->defaultvalue = '';
                } else {
                    $obj->defaultvalue = $field[4];
                }
                if(!isset($field[5])) {
                    $obj->minlength = 0;
                } else {
                    $obj->minlength = $field[5];
                }
                if(!isset($field[6])) {
                    $obj->maxlength = 0;
                } else {
                    // Because maxlength above 256 will not be supported by Unique
                    if($obj->unique) {
                        $obj->maxlength = 250;
                    } else {
                        $obj->maxlength = $field[6];
                    }
                }
                if(!isset($field[7])) {
                    $obj->required = 0;
                } else {
                    $obj->required = $field[7];
                }
                $obj->listing_col = 1;
                
                if(!isset($field[8])) {
                    $obj->popup_vals = "";
                } else {
                    if(is_array($field[8])) {
                        $obj->popup_vals = json_encode($field[8]);
                    } else {
                        $obj->popup_vals = $field[8];
                    }
                }
                $out[] = $obj;
            }
        }
        return $out;
    }

    public static function processDBRow($module, $request, $row)
    {
        $ftypes = ModuleFieldTypes::getFTypes2();

        foreach($module->fields as $field) {
            if(isset($request->{$field['colname']}) || isset($request->{$field['colname'] . "_hidden"})) {

                switch($ftypes[$field['field_type']]) {
                    case 'Checkbox':
                        if(isset($request->{$field['colname']})) {
                            $row->{$field['colname']} = true;
                        } else if(isset($request->{$field['colname'] . "_hidden"})) {
                            $row->{$field['colname']} = false;
                        }
                        break;
                    case 'Date':
                        $null_date = $request->{"null_date_" . $field['colname']};
                        if(isset($null_date) && $null_date == "true") {
                            $request->{$field['colname']} = NULL;
                        } else if($request->{$field['colname']} != "") {
                            $date = $request->{$field['colname']};
                            $d2 = date_parse_from_format("d/m/Y", $date);
                            $request->{$field['colname']} = date("Y-m-d", strtotime($d2['year'] . "-" . $d2['month'] . "-" . $d2['day']));
                        } else {
                            $request->{$field['colname']} = date("Y-m-d");
                        }
                        $row->{$field['colname']} = $request->{$field['colname']};
                        break;
                    case 'Datetime':
                        $null_date = $request->{"null_date_" . $field['colname']};
                        if(isset($null_date) && $null_date == "true") {
                            $request->{$field['colname']} = NULL;
                        } else if($request->{$field['colname']} != "") {
                            $date = $request->{$field['colname']};
                            $d2 = date_parse_from_format("d/m/Y h:i A", $date);
                            $request->{$field['colname']} = date("Y-m-d H:i:s", strtotime($d2['year'] . "-" . $d2['month'] . "-" . $d2['day'] . " " . substr($date, 11)));
                        } else {
                            $request->{$field['colname']} = date("Y-m-d H:i:s");
                        }
                        $row->{$field['colname']} = $request->{$field['colname']};
                        break;
                    case 'Dropdown':
                        if($request->{$field['colname']} == 0) {
                            if(starts_with($field['popup_vals'], "@")) {
                                $request->{$field['colname']} = DB::raw('NULL');
                            } else if(starts_with($field['popup_vals'], "[")) {
                                $request->{$field['colname']} = "";
                            }
                        }
                        $row->{$field['colname']} = $request->{$field['colname']};
                        break;
                    case 'Multiselect':
                        // TODO: Bug fix
                        $row->{$field['colname']} = json_encode($request->{$field['colname']});
                        break;
                    case 'Password':
                        $row->{$field['colname']} = bcrypt($request->{$field['colname']});
                        break;
                    case 'Taginput':
                        // TODO: Bug fix
                        $row->{$field['colname']} = json_encode($request->{$field['colname']});
                        break;
                    case 'Files':
                        $files = json_decode($request->{$field['colname']});
                        $files2 = array();
                        foreach($files as $file) {
                            $files2[] = "" . $file;
                        }
                        $row->{$field['colname']} = json_encode($files2);
                        break;
                    default:
                        $row->{$field['colname']} = $request->{$field['colname']};
                        break;
                }
            }
        }
        return $row;
    }
	
	public static function tableName(){
		return '{{modules}}';
	}
}
