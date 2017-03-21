<?php

use yii\db\Migration;
use backend\models\Module;

class Create_yyy2s_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Yyy2", 'yyy2s', 'gfdf', 'yyyfa2', [
            [
                "colname" => "gfdf",
                "label" => "gfdf",
                "field_type" => "Currency",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 11,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 0,
                "listing_col" => 0
            ]
        ]);
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		$tableSchema = Yii::$app->db->schema->getTableSchema('yyy2s');
        if($tableSchema !== null) {
            $this->dropTable('yyy2s');
        }
    }
}