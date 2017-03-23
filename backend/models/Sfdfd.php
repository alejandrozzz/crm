<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Sfdfd extends ActiveRecord

{
    protected $table = 'sfdfd';

    public static function tableName()
    {
        return '{{sfdfd}}';
    }
}