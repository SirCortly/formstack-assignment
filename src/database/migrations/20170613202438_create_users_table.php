<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function change()
    {
        $users = $this->table('users');
        $users->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->addColumn('firstname', 'string')
            ->addColumn('lastname', 'string')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }
}
