<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Ht extends ActiveRecord

{
    protected $table = 'ht';

    public static function tableName()
    {
        return '{{ht}}';
    }
}