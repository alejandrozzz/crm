<?php

use yii\db\Migration;

class Create_xxxs_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Xxx", 'xxxs', 't', 'fa-xxx', [
            [
                "colname" => "t",
                "label" => "t",
                "field_type" => "Checkbox",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('xxxs');
        if($tableSchema !== null) {
            $this->dropTable('xxxs');
        }
    }
}