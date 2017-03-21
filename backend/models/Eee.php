<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Eee extends ActiveRecord

{
    protected $table = 'eee';

    public static function tableName()
    {
        return '{{eee}}';
    }
}