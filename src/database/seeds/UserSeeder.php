<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Seed users table with test data
     */
    public function run()
    {
        // Make sure we truncate before reseeding
        $users = $this->table('users');
        $users->truncate();

        $data = [
            [
                'email' => 'john.lennon@beatles.com',
                'password' => password_hash('john', PASSWORD_DEFAULT),
                'firstname' => 'John',
                'lastname' => 'Lennon',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'george.harrison@beatles.com',
                'password' => password_hash('george', PASSWORD_DEFAULT),
                'firstname' => 'George',
                'lastname' => 'Harrison',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'paul.mcartney@beatles.com',
                'password' => password_hash('paul', PASSWORD_DEFAULT),
                'firstname' => 'Paul',
                'lastname' => 'Mcartney',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'ringo.starr@beatles.com',
                'password' => password_hash('ringo', PASSWORD_DEFAULT),
                'firstname' => 'Ringo',
                'lastname' => 'Starr',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        $users->insert($data)
            ->save();
    }
}

