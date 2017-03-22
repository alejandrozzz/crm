<?php

use yii\db\Migration;
use backend\models\Module;

class Create_yyuys_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Yyuy", 'yyuys', 'name', 'yuyuy', [
            [
                "colname" => "name",
                "label" => "Name",
                "field_type" => "String",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 0,
                "listing_col" => 0,
                "popup_vals" =>  @,
            ], [
                "colname" => "test",
                "label" => "test",
                "field_type" => "String",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 0,
                "listing_col" => 0,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('yyuys');
        if($tableSchema !== null) {
            $this->dropTable('yyuys');
        }
    }
}