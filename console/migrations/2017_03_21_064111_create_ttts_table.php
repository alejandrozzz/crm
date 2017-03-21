<?php

use yii\db\Migration;

class Create_ttts_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Ttt", 'ttts', 'r', 'ttt', [
            [
                "colname" => "r",
                "label" => "r",
                "field_type" => "Name",
                "unique" => 1,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 256,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('ttts');
        if($tableSchema !== null) {
            $this->dropTable('ttts');
        }
    }
}