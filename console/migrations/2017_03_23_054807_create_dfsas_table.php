<?php

use yii\db\Migration;
use backend\models\Module;

class Create_dfsas_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Dfsa", 'dfsas', 'dsfaad', 'dsafdfd', [
            [
                "colname" => "dsfaad",
                "label" => "dsfaf",
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('dfsas');
        if($tableSchema !== null) {
            $this->dropTable('dfsas');
        }
    }
}