<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Yt extends ActiveRecord

{
    protected $table = 'yt';

    public static function tableName()
    {
        return '{{yt}}';
    }
}