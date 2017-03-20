<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;
use yii\db\Schema;

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
        $module = Module::find()->where('id = '.$request['module_id'])->one();
        $module_id = $request['module_id'];
		
        $field = self::find()->where(['colname' => (string)$request['ModuleFields']['colname'], 'module' => (int)$module_id])->one();
        if(!isset($field->id)) {
			
            $field = new ModuleFields();
            $field->colname = $request['ModuleFields']['colname'];
            $field->label = $request['ModuleFields']['label'];
            $field->module = $request['module_id'];
            $field->field_type = (int)$request['ModuleFields']['field_type'];
			
            if($request['ModuleFields']['unique']) {
                $field->unique = 1;
            } else {
                $field->unique = 0;
            }
            $field->defaultvalue = $request['ModuleFields']['defaultvalue'];
            if($request['ModuleFields']['minlength'] == "") {
                $request['ModuleFields']['minlength'] = 0;
            }
            if($request['ModuleFields']['maxlength'] == "") {
                if(in_array($request['ModuleFields']['field_type'], [1, 8, 16, 17, 19, 20, 22, 23])) {
                    $request['ModuleFields']['maxlength'] = 256;
                } else if(in_array($request['ModuleFields']['field_type'], [14])) {
                    $request['ModuleFields']['maxlength'] = 20;
                } else if(in_array($request['ModuleFields']['field_type'], [3, 6, 10, 13])) {
                    $request['ModuleFields']['maxlength'] = 11;
                }
            }
            $field->minlength = $request['ModuleFields']['minlength'];
            if($request['ModuleFields']['maxlength'] != null && $request['ModuleFields']['maxlength'] != "") {
                $field->maxlength = $request['ModuleFields']['maxlength'];
            }
            if($request['ModuleFields']['required']) {
                $field->required = 1;
            } else {
                $field->required = 0;
            }
            if($request['ModuleFields']['listing_col']) {
                $field->listing_col = 1;
            } else {
                $field->listing_col = 0;
            }
            if($request['ModuleFields']['field_type'] == 7 || $request['ModuleFields']['field_type'] == 15 || $request['ModuleFields']['field_type'] == 18 || $request['ModuleFields']['field_type'] == 20) {
                if($request['ModuleFields']['popup_value_type'] == 1) {
                    $field->popup_vals = "@" . $request['ModuleFields']['popup_vals'];
                } else if($request['ModuleFields']['popup_value_type'] == 2) {
                    $request['ModuleFields']['popup_vals'] = json_encode($request['ModuleFields']['popup_vals']);
                    $field->popup_vals = $request['ModuleFields']['popup_vals'];
                }
            } else {
                $field->popup_vals = "";
            }            
            // Get number of Module fields
            $modulefields = self::find()->where(['module'=>$module_id])->one();
            
            // Create Schema for Module Field when table is not exist
            if(!Yii::$app->db->schema->getTableSchema($module['name_db'])) {
				$tableOptions = null;
				
					$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
				
				$m = new Migration();
				$m->createTable($module['name_db'], [
					'id' => Schema::TYPE_PK,
					'created_at' => Schema::TYPE_INTEGER,
					'updated_at' => Schema::TYPE_INTEGER
				], $tableOptions);
            } else if(Yii::$app->db->schema->getTableSchema($module['name_db']) && count($modulefields) == 0) {
                // create SoftDeletes + Timestamps for module with existing table
				Yii::$app->db->createCommand()
				 ->update($module['name_db'], ['created_at' => time(), 'updated_at' => time()])
				 ->execute();
            }
			$table = Yii::$app->db->schema->getTableSchema($module['name_db']);
			
            // Create Schema for Module Field when table is exist
            if(!isset($table->columns[$field->colname])) {
				$m = new Migration();
				$m->addColumn($module['name_db'],$field->colname,Schema::TYPE_STRING . " DEFAULT 0");
                
            }
        }
        //unset($field->module_obj);
        // field_type conversion to integer
        if(is_string($field->field_type)) {
            $ftypes =array_flip(ModuleFieldTypes::getFTypes());
			
            $field->field_type = $ftypes[$field->field_type];
        }
        $field->save();
        
        return $field->id;
    }

    public static function tableName(){
        return '{{module_fields}}';
    }
}