<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170625103740 extends AbstractMigration
{
    public function getDescription()
    {
        $description = 'This migration adds new fields to identity table';
        return $description;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable('identity');
        $table->addColumn('identity_range', 'integer', ['notnull'=>false]);
        $table->addColumn('identity_id', 'string', ['notnull'=>true, 'length'=>50]);
        $table->addColumn('description', 'text', ['notnull'=>false]);
        $table->addColumn('date_of_issue', 'datetime', ['notnull'=>true]);
        $table->addColumn('date_of_expire', 'datetime', ['notnull'=>true]);
        $table->addColumn('authority', 'string', ['notnull'=>false, 'length'=>250]);
        $table->addColumn('is_valid', 'boolean', ['notnull'=>true, 'default'=>1]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable('identity');
        $table->dropColumn('identity_range');
        $table->dropColumn('identity_id');
        $table->dropColumn('description');
        $table->dropColumn('date_of_issue');
        $table->dropColumn('date_of_expire');
        $table->dropColumn('authority');
        $table->dropColumn('is_valid');
    }
}
