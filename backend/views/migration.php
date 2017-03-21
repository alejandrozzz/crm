<?php

use yii\db\Migration;

class __migration_class_name__ extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("__module_name__", '__db_table_name__', '__view_column__', '__fa_icon__', [
            __generated__
        ]);
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		$tableSchema = Yii::$app->db->schema->getTableSchema('__db_table_name__');
        if($tableSchema !== null) {
            $this->dropTable('__db_table_name__');
        }
    }
}