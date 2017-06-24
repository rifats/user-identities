<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20170623232450
 * @package Migrations
 */
class Version20170623232450 extends AbstractMigration
{
    public function getDescription()
    {
        $description = 'This migration adds user field to identity table';
        return $description;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable('identity');
        $table->addColumn('user_id', 'integer', ['notnull'=>true]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable('identity');
        $table->dropColumn('user_id');
    }
}
