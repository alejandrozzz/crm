<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Fgadgf extends ActiveRecord

{
    protected $table = 'fgadgf';

    public static function tableName()
    {
        return '{{fgadgf}}';
    }
}