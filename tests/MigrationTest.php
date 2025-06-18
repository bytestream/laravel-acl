<?php namespace Kodeine\Acl\Tests;

use Illuminate\Support\Facades\Schema;

class MigrationTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */

    public function testItCanMigrate()
    {
        $this->migrate();

        foreach ($this->getTablesNames() as $table) {
            $this->assertTrue(Schema::hasTable($table), "The table [$table] not found in the database.");
        }
    }
}
