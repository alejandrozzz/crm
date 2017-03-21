<?php

use yii\db\Migration;
use backend\models\Module;

class Create_tests_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Test", 'tests', 'fd', 'tt', [
            [
                "colname" => "fd",
                "label" => "fdfd",
                "field_type" => "Currency",
                "unique" => 1,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 11,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('tests');
        if($tableSchema !== null) {
            $this->dropTable('tests');
        }
    }
}