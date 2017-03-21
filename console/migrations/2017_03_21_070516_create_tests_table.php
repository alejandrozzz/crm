<?php

use yii\db\Migration;

class Create_tests_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Test", 'tests', 'fa-test', 'fa-test', [
            [
                "colname" => "fa-test",
                "label" => "test",
                "field_type" => "Address",
                "unique" => 1,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('tests');
        if($tableSchema !== null) {
            $this->dropTable('tests');
        }
    }
}