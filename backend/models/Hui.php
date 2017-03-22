<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Hui extends ActiveRecord

{
    protected $table = 'hui';

    public static function tableName()
    {
        return '{{hui}}';
    }
}