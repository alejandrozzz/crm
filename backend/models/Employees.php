<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Employees extends ActiveRecord

{
    protected $table = 'employees';

    public static function tableName()
    {
        return '{{employees}}';
    }
}