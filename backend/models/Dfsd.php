<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Dfsd extends ActiveRecord

{
    protected $table = 'dfsd';

    public static function tableName()
    {
        return '{{dfsd}}';
    }
}