<?php

use yii\db\Migration;

class m170304_165646_alter_book_table extends Migration
{
    public function up()
    {
        $this->addColumn('book', 'pdf', $this->string());
        $this->addColumn('book', 'epub', $this->string());
        $this->addColumn('book', 'doc', $this->string());
        $this->addColumn('book', 'txt', $this->string());
        $this->addColumn('book', 'fb2', $this->string());
    }

    public function down()
    {
        $this->dropColumn('book', 'pdf');
        $this->dropColumn('book', 'epub');
        $this->dropColumn('book', 'doc');
        $this->dropColumn('book', 'txt');
        $this->dropColumn('book', 'fb2');
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
