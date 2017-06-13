<?php

use Phinx\Seed\AbstractSeed;
use Faker\Factory as Faker;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Faker::create();

        // Make sure we truncate before reseeding
        $users = $this->table('users');
        $users->truncate();

        $data = [];
        for ($i = 0; $i < 30; $i++) {
            $data[] = [
                'email' => $faker->email,
                'password' => password_hash($faker->password, PASSWORD_DEFAULT),
                'firstname' => $faker->firstName,
                'lastname' => $faker->lastName,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        $users->insert($data)
            ->save();
    }
}
