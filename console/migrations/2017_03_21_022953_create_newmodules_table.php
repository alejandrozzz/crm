<?php

use yii\db\Migration;
use backend\models\Module;

class Create_newmodules_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Newmodule", 'newmodules', 'a', 'fa-new', [
            [
                "colname" => "a",
                "label" => "a",
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
            ], [
                "colname" => "b",
                "label" => "b",
                "field_type" => "Date",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 0,
                "listing_col" => 1
            ], [
                "colname" => "c",
                "label" => "c",
                "field_type" => "Dropdown",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 0,
                "listing_col" => 1,
                "popup_vals" => "@menus",
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('newmodules');
        if($tableSchema !== null) {
            $this->dropTable('newmodules');
        }
    }
}