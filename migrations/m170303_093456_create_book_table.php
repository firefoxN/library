<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book`.
 */
class m170303_093456_create_book_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->unique(),
            'short_description' => $this->text(),
            'full_description' => $this->text(),
            'autor_id' => $this->integer(),
            'category_id' => $this->integer()->notNull(),
            'preview' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('book');
    }
}
