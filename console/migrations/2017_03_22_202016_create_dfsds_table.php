<?php

use yii\db\Migration;
use backend\models\Module;

class Create_dfsds_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Dfsd", 'dfsds', 'fdsadsd', 'fdssdfd', [
            [
                "colname" => "fdsadsd",
                "label" => "fdsafd",
                "field_type" => "Address",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 256,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 0,
                "listing_col" => 1
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('dfsds');
        if($tableSchema !== null) {
            $this->dropTable('dfsds');
        }
    }
}