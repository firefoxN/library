<?php

use yii\db\Migration;

class m170303_100240_link_category_book_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //creates index for book's column category_id
        $this->createIndex(
            'idx-book-category_id',
            'book',
            'category_id'
        );

        //add foreign key for table category
        $this->addForeignKey(
            'fk-book-category_id',
            'book',
            'category_id',
            'category',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-book-category_id', 'book');
        $this->dropIndex('idx-book-category_id', 'book');
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
