<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Companies extends ActiveRecord

{
    protected $table = 'companies';

    public static function tableName()
    {
        return '{{companies}}';
    }
}