<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\DupaHelper;
use yii\db\Migration;

class ModuleFields extends ActiveRecord{

    protected $table = 'module_fields';

    protected $fillable = [
        "colname", "label", "module", "field_type", "unique", "defaultvalue", "minlength", "maxlength", "required", "listing_col", "popup_vals"
    ];

    protected $hidden = [

    ];

    public function rules(){

        return [
            [['colname'], 'string'],
            [['label'], 'string'],
            [['module'], 'integer'],
            [['field_type'], 'integer'],
            [['unique'], 'integer'],
            [['defaultvalue'], 'string'],
            [['minlength'], 'integer'],
            [['maxlength'], 'integer'],
            [['required'], 'integer'],
            [['listing_col'], 'integer'],
            [['popup_vals'], 'string']
        ];
    }

    public static function getModuleFields($moduleName)
    {
        $module = Module::find()->where('name', $moduleName)->one();

        $fields = $module->getTableSchema()->getColumnNames();
        $ftypes = ModuleFieldTypes::getFTypes();

        $fields_popup = array();
        $fields_popup['id'] = null;

        // Set field type (e.g. Dropdown/Taginput) in String Format to field Object
        foreach($fields as $f) {
            $f->field_type_str = array_search($f->field_type, $ftypes);
            $fields_popup [$f->colname] = $f;
        }
        return $fields_popup;
    }
	
	public static function createField($request)
    {
        $module = Module::find($request['module_id'])->one();
        $module_id = $request['module_id'];
        
        $field = ModuleFields::find()->where('colname', $request->colname)->where('module', $module_id)->one();
        if(!isset($field->id)) {
            $field = new ModuleFields;
            $field->colname = $request->colname;
            $field->label = $request->label;
            $field->module = $request->module_id;
            $field->field_type = $request->field_type;
            if($request->unique) {
                $field->unique = true;
            } else {
                $field->unique = false;
            }
            $field->defaultvalue = $request->defaultvalue;
            if($request->minlength == "") {
                $request->minlength = 0;
            }
            if($request->maxlength == "") {
                if(in_array($request->field_type, [1, 8, 16, 17, 19, 20, 22, 23])) {
                    $request->maxlength = 256;
                } else if(in_array($request->field_type, [14])) {
                    $request->maxlength = 20;
                } else if(in_array($request->field_type, [3, 6, 10, 13])) {
                    $request->maxlength = 11;
                }
            }
            $field->minlength = $request->minlength;
            if($request->maxlength != null && $request->maxlength != "") {
                $field->maxlength = $request->maxlength;
            }
            if($request->required) {
                $field->required = true;
            } else {
                $field->required = false;
            }
            if($request->listing_col) {
                $field->listing_col = true;
            } else {
                $field->listing_col = false;
            }
            if($request->field_type == 7 || $request->field_type == 15 || $request->field_type == 18 || $request->field_type == 20) {
                if($request->popup_value_type == "table") {
                    $field->popup_vals = "@" . $request->popup_vals_table;
                } else if($request->popup_value_type == "list") {
                    $request->popup_vals_list = json_encode($request->popup_vals_list);
                    $field->popup_vals = $request->popup_vals_list;
                }
            } else {
                $field->popup_vals = "";
            }            
            // Get number of Module fields
            $modulefields = ModuleFields::where('module', $module_id)->get();
            
            // Create Schema for Module Field when table is not exist
            if(!Schema::hasTable($module->name_db)) {
                Schema::create($module->name_db, function ($table) {
                    $table->increments('id');
                    $table->softDeletes();
                    $table->timestamps();
                });
            } else if(Schema::hasTable($module->name_db) && count($modulefields) == 0) {
                // create SoftDeletes + Timestamps for module with existing table
                Schema::table($module->name_db, function ($table) {
                    $table->softDeletes();
                    $table->timestamps();
                });
            }
            // Create Schema for Module Field when table is exist
            if(!Schema::hasColumn($module->name_db, $field->colname)) {
                Schema::table($module->name_db, function ($table) use ($field, $module) {
                    // $table->string($field->colname);
                    // createUpdateFieldSchema()
                    $field->module_obj = $module;
                    Module::create_field_schema($table, $field, false);
                });
            }
        }
        unset($field->module_obj);
        // field_type conversion to integer
        if(is_string($field->field_type)) {
            $ftypes = ModuleFieldTypes::getFTypes();
            $field->field_type = $ftypes[$field->field_type];
        }
        $field->save();
        
        return $field->id;
    }

    public static function tableName(){
        return '{{module_fields}}';
    }
}