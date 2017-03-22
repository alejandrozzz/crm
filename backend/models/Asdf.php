<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Asdf extends ActiveRecord

{
    protected $table = 'asdf';

    public static function tableName()
    {
        return '{{asdf}}';
    }
}