<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Dsads extends ActiveRecord

{
    protected $table = 'dsads';

    public static function tableName()
    {
        return '{{dsads}}';
    }
}