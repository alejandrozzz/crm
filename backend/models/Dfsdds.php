<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Dfsdds extends ActiveRecord

{
    protected $table = 'dfsdds';

    public static function tableName()
    {
        return '{{dfsdds}}';
    }
}