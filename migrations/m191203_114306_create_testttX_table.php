<?php

use yii\db\Migration;

/**
 * Handles the creation of table `testttX`.
 */
class m191203_114306_create_testttX_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('testttX', [
            't_id' => $this->primaryKey(),
			't_name' => $this->string()->notNull(),
			't_desc' => $this->string()->notNull(),
        ]);
		
		//INSERT values
		$this->batchInsert('testttX', ['t_name', 't_desc'], [
        ['category1', 'desc1'], 
        ['category2', 'desc2'], 
        ['category3', 'desc3']
		]);
		
		
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('testttX');
    }
}
