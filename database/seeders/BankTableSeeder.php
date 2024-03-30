<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('banks')->insert([
            [
                'name' => 'Cash',
                'account_number' => 'Cash',
                'phone_number' => '0175******',
                'branch_name' => '',
                'balance' => '0',
                'status' => '0',
            ],
            [
                'name' => 'Bkash',
                'account_number' => 'Bkash',
                'phone_number' => '0175******',
                'branch_name' => '',
                'balance' => '0',
                'status' => '0',
            ],
            [
                'name' => 'Nagad',
                'account_number' => 'Nagad',
                'phone_number' => '0175******',
                'branch_name' => '',
                'balance' => '0',
                'status' => '0',
            ],
            [
                'name' => 'Rocket',
                'account_number' => 'Rocket',
                'phone_number' => '0175******',
                'branch_name' => '',
                'balance' => '0',
                'status' => '0',
            ],
            [
                'name' => 'Online Bank',
                'account_number' => 'Online Bank',
                'phone_number' => '',
                'branch_name' => '',
                'balance' => '0',
                'status' => '0',
            ],
        ]);

        DB::table('site_settings')->insert([
            [
                'title' => 'Site Title Here',
                'copyright' => 'Copyright text here',
                'logo' => '',
                'favicon' => '',
            ]
        ]);
    }
}
