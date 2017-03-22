<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Test5 extends ActiveRecord

{
    protected $table = 'test5';

    public static function tableName()
    {
        return '{{test5}}';
    }
}