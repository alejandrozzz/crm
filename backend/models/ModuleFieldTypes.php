<?php

namespace backend\models;


use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\DupaHelper;
use yii\db\Migration;


class ModuleFieldTypes extends ActiveRecord
{

    protected $table = 'module_field_types';
    
    protected $fillable = [
        "name"
    ];

    protected $hidden = [
    
    ];
    public function fields(){
        return ['name'];
    }
    public function rules(){

        return [
            [['name'], 'required']
        ];
    }

    // ModuleFieldTypes::getFTypes()
    public static function getFTypes()
    {
        $fields = ModuleFieldTypes::find()->all();
        $fields2 = array();
        foreach($fields as $field) {
            $fields2[$field['name']] = $field['id'];
        }
        return $fields2;
    }
    
    // ModuleFieldTypes::getFTypes2()
    public static function getFTypes2()
    {
        $fields = ModuleFieldTypes::find()->all();
        $fields2 = array();
        foreach($fields as $field) {
            $fields2[$field['id']] = $field['name'];
        }
        return $fields2;
    }
    public static function tableName(){
        return '{{%module_field_types}}';
    }
}