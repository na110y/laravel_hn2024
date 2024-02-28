<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::create([
            'name' => 'Duc Thinh',
            'email' => 'thinh@gmail.com',
            'password' => bcrypt('123123')
        ]);
    }
}
