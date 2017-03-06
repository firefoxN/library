<?php

use yii\db\Migration;

class m170303_101107_link_author_book_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //create index for book's column author_id
        $this->createIndex(
            'idx-book-author_id',
            'book',
            'author_id'
        );

        //add foreign key for table author
        $this->addForeignKey(
            'fk-book-author_id',
            'book',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-book-author_id', 'book');
        $this->dropIndex('idx-book-author_id', 'book');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
