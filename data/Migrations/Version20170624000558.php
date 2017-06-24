<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170624000558 extends AbstractMigration
{
    public function getDescription()
    {
        $description = 'This migration adds indexes and foreign key constraints.';
        return $description;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable('identity');
        //$table->addIndex(['date_created'], 'identity_date_created_index');
        $table->addIndex(['user_id'], 'user_id_index');
        $table->addForeignKeyConstraint('user', ['user_id'], ['id'], [], 'identity_user_id_fk');

        //$table = $schema->getTable('user');
        //$table->addIndex(['date_created'], 'user_date_created_index');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable('identity');
        $table->removeForeignKey('identity_user_id_fk');
        $table->dropIndex('user_id_index');
    }
}
