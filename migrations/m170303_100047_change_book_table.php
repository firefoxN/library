<?php

use yii\db\Migration;

class m170303_100047_change_book_table extends Migration
{
    public function up()
    {
        $this->renameColumn('book', 'autor_id', 'author_id');
    }

    public function down()
    {
        $this->renameColumn('book', 'author_id', 'autor_id');
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
