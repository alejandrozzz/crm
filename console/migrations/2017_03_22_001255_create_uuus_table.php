<?php

use yii\db\Migration;
use backend\models\Module;

class Create_uuus_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Uuu", 'uuus', 'gf', 'uuu', [
            [
                "colname" => "gf",
                "label" => "fg",
                "field_type" => "String",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 1,
                "listing_col" => 1,
                "popup_vals" =>  @companies,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('uuus');
        if($tableSchema !== null) {
            $this->dropTable('uuus');
        }
    }
}