<?php

use yii\db\Migration;

/**
 * Handles the creation of table `booking_cph_v2_hote`.
 */
class m210407_130948_create_booking_cph_v2_hote_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('booking_cph_v2_hote', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('booking_cph_v2_hote');
    }
}
