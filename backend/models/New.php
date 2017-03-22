<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class New extends ActiveRecord

{
    protected $table = 'new';

    public static function tableName()
    {
        return '{{new}}';
    }
}