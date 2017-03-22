<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class R extends ActiveRecord

{
    protected $table = 'r';

    public static function tableName()
    {
        return '{{r}}';
    }
}