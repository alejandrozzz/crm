<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Aq extends ActiveRecord

{
    protected $table = 'aq';

    public static function tableName()
    {
        return '{{aq}}';
    }
}