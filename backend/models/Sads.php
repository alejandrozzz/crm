<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Sads extends ActiveRecord

{
    protected $table = 'sads';

    public static function tableName()
    {
        return '{{sads}}';
    }
}