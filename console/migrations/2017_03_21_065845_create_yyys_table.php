<?php

use yii\db\Migration;

class Create_yyys_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Yyy", 'yyys', 'test', 'yyyfa', [
            [
                "colname" => "test",
                "label" => "test",
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('yyys');
        if($tableSchema !== null) {
            $this->dropTable('yyys');
        }
    }
}