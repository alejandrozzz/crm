<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Fsafd extends ActiveRecord

{
    protected $table = 'fsafd';

    public static function tableName()
    {
        return '{{fsafd}}';
    }
}