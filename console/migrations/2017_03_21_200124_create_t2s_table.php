<?php

use yii\db\Migration;
use backend\models\Module;

class Create_t2s_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("T2", 't2s', 'fdad', 'ttttt', [
            [
                "colname" => "fdad",
                "label" => "fdas",
                "field_type" => "Textarea",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 256,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 0,
                "listing_col" => 0,
                "popup_vals" =>  "",
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('t2s');
        if($tableSchema !== null) {
            $this->dropTable('t2s');
        }
    }
}