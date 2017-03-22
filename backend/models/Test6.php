<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Test6 extends ActiveRecord

{
    protected $table = 'test6';

    public static function tableName()
    {
        return '{{test6}}';
    }
}