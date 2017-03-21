<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\helpers\DupaHelper;
use yii\db\Migration;

class Menu extends ActiveRecord

{
    protected $table = 'test';
	
	public static function tableName(){
		return '{{menus}}';
	}
}