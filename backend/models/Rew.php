<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Rew extends ActiveRecord

{
    protected $table = 'rew';

    public static function tableName()
    {
        return '{{rew}}';
    }
}