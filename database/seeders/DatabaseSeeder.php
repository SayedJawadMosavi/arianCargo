<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionTableSeeder::class,
            UserTableSeeder::class,


        ]);
        // // \App\Models\User::factory(10)->create();
        // DB::table('users')->insert(
        //     array(
        //         'name' => 'admin',
        //         'email' => 'admin@gmail.com',
        //         'password' => '$2y$10$9nvTOpIXKNVQHYlulV/JOeKcRmbmCqWF.5MzZjDdP4enlqJhex1LC',
        //     )
        // );

        DB::table('settings')->insert(
            array(
                'user_id' => 1,
                'branch_id' => 1,
                'logo' => 'images/logo.png',
                'name_en' => 'Retail MIS ',
                'mobile1' => '0780100676',
                'mobile2' => '0700664433',
                'email' => 'info@nethub.af',
                'address_en' => 'Kabul',
                'facebook' => 'facebook.com/nethub.af',
                'twitter' => 'ftw.com',
                'currency_id' => 2,
                // 'youtbue' => 'you.com',
                'instagram' => 'insta.com',
                'linkedin' => 'linkedin.com',
                'meta_keyword' => 'Afghan stone, afghan mining, stone mining, stone process',
                'meta_description' => 'Afghan stone the leading factory in extracting, processing and selling mineral stones',
            )
        );
        DB::table('branches')->insert(
            array(
                'name' => 'Nethub',
                'contact_person' => 'Hamidy ',
                'address' => 'Shahr-e-naw Kabul, Afghanistan',
                'mobile1' => '0780100676',
                'mobile2' => '0700664433',
                'active' => 1,
                'user_id' => 1
            )
        );
        DB::table('currencies')->insert(
            array(
                'name' => 'AFN',
                'active' => 1,
                'default' => 1,
                'branch_id' => 1,
                'user_id' => 1
            )
        );
        DB::table('currencies')->insert(
            array(
                'name' => 'USD',
                'active' => 1,
                'default' => 0,
                'branch_id' => 1,
                'user_id' => 1
            )
        );
        DB::table('units')->insert(
            array(
                'name' => 'Pcs',
                'short_name' => 'Pcs',
                'active' => 1,
                'branch_id' => 1,
                'user_id' => 1
            )
        );
        DB::table('categories')->insert(
            array(
                'name' => 'BEKO',
                'active' => 1,
                'user_id' => 1
            )
        );

        DB::table('accounts')->insert(
            array(
                'name' => 'Safe afn',
                'amount' => 0,
                'currency_id' => 1,
                'branch_id' => 1,
                'active' => 1,
                'default' => 1,
                'user_id' => 1
            )
        );
        DB::table('accounts')->insert(
            array(
                'name' => 'Safe usd',
                'amount' => 0,
                'currency_id' => 2,
                'branch_id' => 1,
                'active' => 1,
                'default' => 1,
                'user_id' => 1
            )
        );
        DB::table('clients')->insert(
            array(
                'name' => 'walkin AFN',
                'type' => 'walkin',
                'branch_id' => 1,
                'active' => 1,
                'user_id' => 1
            )
        );
        DB::table('client_currencies')->insert(
            array(
                'client_id' => 1,
                'currency_id' => 1,
                'branch_id' => 1,
                'amount' => 0,
            )
        );

        DB::table('clients')->insert(
            array(
                'name' => 'walkin USD',
                'type' => 'walkin',
                'branch_id' => 1,
                'active' => 1,
                'user_id' => 1
            )
        );
        DB::table('client_currencies')->insert(
            array(
                'client_id' => 2,
                'currency_id' => 2,
                'branch_id' => 1,
                'amount' => 0,
            )
        );
        DB::table('stocks')->insert(
            array(
                'name' => 'Kabul',
                'contact_person' => 'Ahmad',
                'user_id' => 1,
                'branch_id' => 1,
            )
        );

        DB::table('rates')->insert(
            array(
                'from_treasury' => 1,
                'to_treasury' => 2,
                'rate' => 70.0000,
                'operation' => 'divide',
                'user_id' => 1,
                'branch_id' => 1,
            )
        );
        DB::table('rates')->insert(
            array(
                'from_treasury' => 2,
                'to_treasury' => 1,
                'rate' => 70.0000,
                'operation' => 'multiply',
                'user_id' => 1,
                'branch_id' => 1,
            )
        );


    }
}
