<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170623230800 extends AbstractMigration
{
    public function getDescription()
    {
        $description = 'This is the initial migration which creates identity table.';

        return $description;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('identity');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('ident_type', 'string', ['notnull'=>true, 'length'=>50]);
        $table->addColumn('name', 'string', ['notnull'=>true, 'length'=>30]);
        $table->addColumn('surname', 'string', ['notnull'=>true, 'length'=>40]);
        $table->addColumn('date_created', 'datetime', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('identity');
    }
}
