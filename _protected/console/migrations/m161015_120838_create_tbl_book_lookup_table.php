<?php

use yii\db\Migration;

/**
 * Handles the creation for table `tbl_book_lookup`.
 */
class m161015_120838_create_tbl_book_lookup_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_book_lookup', [
            'id' => $this->primaryKey(),
            'sunshade' => $this->string(20)->notNull(),
            'bookinfo_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
            'guest_id' => $this->integer()->notNull(),
            'milestonegroup_id' => $this->integer()->notNull(),
            'bookstate' => $this->string(20)->notNull(),
            'booktoken' => $this->string(255)->notNull(),
            'deleted' => $this->integer()->defaultValue(0),
            'booked_at' => $this->string(255)->notNull(),
        ]);

         // creates index for column `bookinfo_id`
        $this->createIndex(
            'idx-tbl_book_lookup-bookinfo_id',
            'tbl_book_lookup',
            'bookinfo_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-tbl_book_lookup-bookinfo_id',
            'tbl_book_lookup',
            'bookinfo_id',
            'tbl_bookinfo',
            'id',
            'CASCADE'
        );

         // creates index for column `book_id`
        $this->createIndex(
            'idx-tbl_book_lookup-book_id',
            'tbl_book_lookup',
            'book_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-tbl_book_lookup-book_id',
            'tbl_book_lookup',
            'book_id',
            'tbl_book',
            'id',
            'CASCADE'
        );

         // creates index for column `book_id`
        $this->createIndex(
            'idx-tbl_book_lookup-guest_id',
            'tbl_book_lookup',
            'guest_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-tbl_book_lookup-guest_id',
            'tbl_book_lookup',
            'guest_id',
            'tbl_guest',
            'id',
            'CASCADE'
        );

        // creates index for column `book_id`
        $this->createIndex(
            'idx-tbl_book_lookup-milestonegroup_id',
            'tbl_book_lookup',
            'milestonegroup_id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
         // drops foreign key for table `tbl_bookinfo`
        $this->dropForeignKey(
            'fk-tbl_book_lookup-bookinfo_id',
            'tbl_book_lookup'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-tbl_book_lookup-bookinfo_id',
            'tbl_book_lookup'
        );

         // drops foreign key for table `tbl_book`
        $this->dropForeignKey(
            'fk-tbl_book_lookup-book_id',
            'tbl_book_lookup'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-tbl_book_lookup-book_id',
            'tbl_book_lookup'
        );

         // drops foreign key for table `tbl_book`
        $this->dropForeignKey(
            'fk-tbl_book_lookup-guest_id',
            'tbl_book_lookup'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-tbl_book_lookup-guest_id',
            'tbl_book_lookup'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-tbl_book_lookup-milestonegroup_id',
            'tbl_book_lookup'
        );

        $this->dropTable('tbl_book_lookup');
    }
}
