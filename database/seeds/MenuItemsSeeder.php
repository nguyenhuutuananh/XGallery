<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class MenuItems
 */
class MenuItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu_items')->insert(
            [
                [
                    'name' => 'Dashboard',
                    'link' => '/',
                    'type' => 'header',
                    'ordering' => 0
                ],
                [
                    'name' => 'JAV',
                    'link' => '/jav',
                    'type' => 'header',
                    'ordering' => 0
                ]
            ]
        );
    }
}
