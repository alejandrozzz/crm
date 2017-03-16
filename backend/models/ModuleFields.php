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

    public static function tableName(){
        return '{{module_fields}}';
    }
}