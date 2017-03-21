<?php

use yii\db\Migration;

class Create_dgfagfs_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Dgfagf", 'dgfagfs', 'fdsd', 'gfadgfad', [
            [
                "colname" => "fdsd",
                "label" => "dfd",
                "field_type" => "Currency",
                "unique" => 1,
                "defaultvalue" => "34",
                "minlength" => 4323,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('dgfagfs');
        if($tableSchema !== null) {
            $this->dropTable('dgfagfs');
        }
    }
}