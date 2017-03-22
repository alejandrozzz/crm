<?php

use yii\db\Migration;
use backend\models\Module;

class Create_dfsddss_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Dfsdds", 'dfsddss', 'dfs', 'fdfd', [
            [
                "colname" => "dfs",
                "label" => "fds",
                "field_type" => "Currency",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 11,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('dfsddss');
        if($tableSchema !== null) {
            $this->dropTable('dfsddss');
        }
    }
}