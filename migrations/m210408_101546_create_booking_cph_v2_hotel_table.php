<?php

use yii\db\Migration;

/**
 * Handles the creation of table `booking_cph_v2_hotel`.
 */
class m210408_101546_create_booking_cph_v2_hotel_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('booking_cph_v2_hotel', [
            'book_id'            => $this->primaryKey(),
			'booked_by_user'     => $this->integer()->notNull(),
			'booked_guest'       => $this->string(77)->notNull(),
            'booked_guest_email' => $this->string(77)->notNull(),
            'book_from'          => $this->string(33)->notNull(),
            'book_to'            => $this->string(33)->notNull(),
            'book_to'            => $this->string(33)->notNull(),
            'book_from_unix'     => $this->integer()->notNull(),
            'book_to_unix'       => $this->integer()->notNull(),
            'book_room_id'       => $this->integer()->notNull(),
            'createdAt'          => $this->timestamp(),
            'updatedAt'          => $this->timestamp(),

        ]);
        
        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-post-booked_by_user',
            'booking_cph_v2_hotel', //tb
            'booked_by_user',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('booking_cph_v2_hotel');
    }
}
