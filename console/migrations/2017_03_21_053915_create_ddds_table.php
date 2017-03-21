<?php

use yii\db\Migration;

class Create_ddds_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Ddd", 'ddds', 'ddd', 'ddd', [
            [
                "colname" => "ddd",
                "label" => "ddd",
                "field_type" => "Address",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 256,
                "required" => 1,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('ddds');
        if($tableSchema !== null) {
            $this->dropTable('ddds');
        }
    }
}