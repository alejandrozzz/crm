<?php

use yii\db\Migration;
use backend\models\Module;

class Create_dsadss_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Dsads", 'dsadss', 'dsa', 'sadds', [
            [
                "colname" => "dsa",
                "label" => "Name",
                "field_type" => "Dropdown",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 0,
                "listing_col" => 0,
                "popup_vals" => "@user",
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('dsadss');
        if($tableSchema !== null) {
            $this->dropTable('dsadss');
        }
    }
}