<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Files extends ActiveRecord

{
    protected $table = 'files';

    public static function tableName()
    {
        return '{{files}}';
    }
}