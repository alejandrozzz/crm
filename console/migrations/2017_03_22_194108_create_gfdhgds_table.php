<?php

use yii\db\Migration;
use backend\models\Module;

class Create_gfdhgds_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Gfdhgd", 'gfdhgds', 'sane', 'hgdfhg', [
            [
                "colname" => "sane",
                "label" => "sane",
                "field_type" => "String",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 0,
                "listing_col" => 1,
                "popup_vals" => """",
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('gfdhgds');
        if($tableSchema !== null) {
            $this->dropTable('gfdhgds');
        }
    }
}