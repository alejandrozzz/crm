<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Uuu extends ActiveRecord

{
    protected $table = 'uuu';

    public static function tableName()
    {
        return '{{uuu}}';
    }
}