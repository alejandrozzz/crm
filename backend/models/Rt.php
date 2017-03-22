<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Rt extends ActiveRecord

{
    protected $table = 'rt';

    public static function tableName()
    {
        return '{{rt}}';
    }
}