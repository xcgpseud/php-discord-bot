<?php

use Phinx\Migration\AbstractMigration;

class CreatePinsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('pins')
            ->addColumn('user_id', 'biginteger')
            ->addColumn('data', 'text')
            ->addColumn('created', 'datetime')
            ->create();
    }
}
