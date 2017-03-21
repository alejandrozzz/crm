<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Yyy2 extends ActiveRecord

{
    protected $table = 'yyy2';

    public static function tableName()
    {
        return '{{yyy2}}';
    }
}