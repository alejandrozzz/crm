<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class T2 extends ActiveRecord

{
    protected $table = 't2';

    public static function tableName()
    {
        return '{{t2}}';
    }
}