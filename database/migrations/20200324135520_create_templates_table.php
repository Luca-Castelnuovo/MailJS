<?php

use CQ\DB\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $templates = $this->table('templates', ['id' => false, 'primary_key' => 'id']);
        $templates->addColumn('id', 'uuid')
            ->addColumn('user_id', 'uuid')
            ->addColumn('user_variant', 'string')
            ->addColumn('key_id', 'uuid')
            ->addColumn('name', 'string')
            ->addColumn('captcha_key', 'string', ['null' => true])
            ->addColumn('email_to', 'string')
            ->addColumn('email_replyTo', 'string', ['null' => true])
            ->addColumn('email_cc', 'string', ['null' => true])
            ->addColumn('email_bcc', 'string', ['null' => true])
            ->addColumn('email_fromName', 'string', ['null' => true])
            ->addColumn('email_subject', 'string')
            ->addColumn('email_content', 'text')
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
