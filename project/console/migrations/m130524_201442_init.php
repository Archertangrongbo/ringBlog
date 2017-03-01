<?php

use yii\db\Migration;
use common\models\User;
// use common\models\Category;
// use common\models\Blog;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Crear user table
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Initialize the user admin
        $admin = new User();
        $admin->username = 'admin';
        $admin->setPassword($admin->username);
        $admin->generateAuthKey();
        $admin->enable();
        $admin->created_at = $admin->updated_at = time();

        $this->insert('{{%user}}',$admin->toArray());

        // Crear category table
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'category_name' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'last_account' => $this->integer()->notNull(),
        ], $tableOptions);

        // Initialize the category Default
        // $defaultCategory = new Category();
        // $defaultCategory->category_name = '默认分类';
        // $defaultCategory->created_at = $defaultCategory->updated_at = time();
        // $defaultCategory->last_account = 1;

        // $this->insert('{{%category}}',$defaultCategory->toArray());

        // Crear blog table
        $this->createTable('{{%blog}}', [
            'id' => $this->primaryKey(),
            'blog_title' => $this->string(255)->notNull(),
            'category_id' => $this->integer()->notNull(),
            'blog_content' => $this->text()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'pageviews' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'last_account' => $this->integer()->notNull(),
        ], $tableOptions);

        // Initialize the blog "Hello world"
        // $hello = new Blog();
        // $hello->blog_title = 'Hello world';
        // $hello->category_id = 1;
        // $hello->blog_content = 'Hello world! Welcome to RingBlog!';
        // $hello->pageviews = 0;
        // $hello->created_at = $hello->updated_at = time();
        // $hello->last_account = 1;

        // $this->insert('{{%blog}}',$hello->toArray());

    }

    public function down()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%blog}}');
    }
}