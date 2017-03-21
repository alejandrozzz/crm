<?php

use yii\db\Migration;
use backend\models\Module;

class Create_yyys_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Yyy", 'yyys', 'fda', 'yyyfa', [
            [
                "colname" => "fda",
                "label" => "fsdasd",
                "field_type" => "Date",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('yyys');
        if($tableSchema !== null) {
            $this->dropTable('yyys');
        }
    }
}