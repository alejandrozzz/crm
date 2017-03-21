<?php

use yii\db\Migration;

class Create_testteests_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Testteest", 'testteests', 'gfdf', '', [
            [
                "colname" => "gfdf",
                "label" => "gfdgf",
                "field_type" => "Mobile",
                "unique" => 1,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 20,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('testteests');
        if($tableSchema !== null) {
            $this->dropTable('testteests');
        }
    }
}