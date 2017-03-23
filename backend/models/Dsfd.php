<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Dsfd extends ActiveRecord

{
    protected $table = 'dsfd';

    public static function tableName()
    {
        return '{{dsfd}}';
    }
}