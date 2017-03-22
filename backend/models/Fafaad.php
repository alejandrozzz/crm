<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Fafaad extends ActiveRecord

{
    protected $table = 'fafaad';

    public static function tableName()
    {
        return '{{fafaad}}';
    }
}