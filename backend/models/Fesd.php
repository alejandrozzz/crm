<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Fesd extends ActiveRecord

{
    protected $table = 'fesd';

    public static function tableName()
    {
        return '{{fesd}}';
    }
}