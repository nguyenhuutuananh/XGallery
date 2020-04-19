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
                    'icon' => 'fas fa-home',
                    'ordering' => 1
                ],
                [
                    'name' => 'Adult',
                    'link' => '#',
                    'type' => 'header',
                    'icon' => 'fas fa-female',
                    'ordering' => 2
                ],
                [
                    'name' => 'Jav',
                    'link' => '/jav',
                    'type' => 'item',
                    'icon'=> null,
                    'ordering' => 3
                ],
                [
                    'name' => 'Xiuren',
                    'link' => '/xiuren',
                    'type' => 'item',
                    'icon'=> null,
                    'ordering' => 4
                ],
                [
                    'name' => 'Comics',
                    'link' => '#',
                    'type' => 'header',
                    'icon' => 'fas fa-book',
                    'ordering' => 5
                ],
                [
                    'name' => 'Truyện chọn',
                    'link' => '/truyenchon',
                    'type' => 'item',
                    'icon'=> null,
                    'ordering' => 6
                ],
                [
                    'name' => 'Tools',
                    'link' => '#',
                    'type' => 'header',
                    'icon' => 'fas fa-tools',
                    'ordering' => 7
                ],
                [
                    'name' => 'Flickr',
                    'link' => '/flickr',
                    'type' => 'item',
                    'icon'=> null,
                    'ordering' => 8
                ]
            ]
        );
    }
}
