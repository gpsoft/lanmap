<?php
use Migrations\AbstractMigration;

class CreateNodes extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('nodes');
        $table->addColumn('name', 'string')
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->create();
    }
}
