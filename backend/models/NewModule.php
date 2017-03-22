<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class NewModule extends ActiveRecord

{
    protected $table = 'newmodule';

    public static function tableName()
    {
        return '{{newmodule}}';
    }
}