<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m170303_095020_create_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->unique(),
            'parent_id' => $this->integer()->notNull()->defaultValue(0),
            'short_description' => $this->text(),
            'full_description' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('category');
    }
}
