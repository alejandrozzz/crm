<?php

use yii\db\Migration;
use backend\models\Module;

class Create_eees_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Eee", 'eees', 'sss', 'eee', [
            [
                "colname" => "sss",
                "label" => "sss",
                "field_type" => "Mobile",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 20,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('eees');
        if($tableSchema !== null) {
            $this->dropTable('eees');
        }
    }
}