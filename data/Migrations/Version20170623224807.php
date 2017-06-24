<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170623224807 extends AbstractMigration
{
    public function getDescription()
    {
        $description = 'This is the initial migration which creates user table.';

        return $description;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('user');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('username', 'string', ['notnull'=>true, 'length'=>50]);
        $table->addColumn('email', 'string', ['notnull'=>true, 'length'=>128]);
        $table->addColumn('password', 'string', ['notnull'=>true, 'length'=>256]);
        $table->addColumn('status', 'integer', ['notnull'=>true]);
        $table->addColumn('date_created', 'datetime', ['notnull'=>true]);
        $table->addColumn('pwd_reset_token', 'string', ['notnull'=>false, 'length'=>32]);
        $table->addColumn('pwd_reset_token_creation_date', 'datetime', ['notnull'=>false]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['username'], 'username_idx');
        $table->addUniqueIndex(['email'], 'email_idx');
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('user');
    }
}
