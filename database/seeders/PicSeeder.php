<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 6; $i++) {
            User::firstOrCreate(
                [
                    'email' => 'pic'.$i.'@organization.com',
                ],
                [
                    'name' => 'pic'.$i,
                    'password' => Hash::make('default'),
                    'role' => User::PIC,
                    'phone' => null,
                ]
            );
        }
    }
}
