<?php

use yii\db\Migration;
use backend\models\Module;

class Create_companiess_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Companies", 'companiess', 'name', 'fa-building', [
            [
                "colname" => "name",
                "label" => "Name",
                "field_type" => "Name",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 256,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 1,
                "listing_col" => 1
            ], [
                "colname" => "address",
                "label" => "Address",
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
                "colname" => "phone",
                "label" => "Phone Number",
                "field_type" => "String",
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
                "colname" => "mobile",
                "label" => "Mobile Phone",
                "field_type" => "Mobile",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 20,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('companiess');
        if($tableSchema !== null) {
            $this->dropTable('companiess');
        }
    }
}