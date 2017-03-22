<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Xxx extends ActiveRecord

{
    protected $table = 'xxx';

    public static function tableName()
    {
        return '{{xxx}}';
    }
}