<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'               => 1,
                'name'             => 'Admin',
                'email'            => 'admin@admin.com',
                'password'         => bcrypt('password'),
                'remember_token'   => null,
                'user_type'        => 'Superadmin',
                'contact_number_1' => '',
                'contact_number_2' => '',
                'website'          => '',
            ],
        ];

        User::insert($users);
    }
}
