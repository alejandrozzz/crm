<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Ggg extends ActiveRecord

{
    protected $table = 'ggg';

    public static function tableName()
    {
        return '{{ggg}}';
    }
}