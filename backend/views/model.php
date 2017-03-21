<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class __model_class_name__ extends ActiveRecord

{
    protected $table = '__db_table_name__';

    public static function tableName()
    {
        return '{{__db_table_name__}}';
    }
}