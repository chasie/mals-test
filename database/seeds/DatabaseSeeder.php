<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([[
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
            'activation' => 1,
            'group_id' => 1,
            'password' => '$2y$10$0q8su1uvweCjgeYjWDEx5OCq/BldlhnDy9mmOVBmyyhOd1hIYJhcG',
            'remember_token' => 'KkZK90OUGC9LSg5hCeLApcSmyx5yjvsRhkT7Cw0Tt90BHr9GrMnJAhiINdps',
        ]]
        );
        DB::table('groups')->insert([[
            'id' => '1',
            'name' => 'Administrator',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]]
        );
    }
}
