<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170625110147 extends AbstractMigration
{
    public function getDescription()
    {
        $description = 'This migration adds index to identity table.';
        return $description;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable('identity');
        $table->addUniqueIndex(['identity_type', 'identity_id'], 'identity_type_id_index');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable('identity');
        $table->dropIndex('identity_type_id_index');
    }
}
