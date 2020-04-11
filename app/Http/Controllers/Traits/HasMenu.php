<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers\Traits;

use App\MenuItems;

trait HasMenu
{
    /**
     * @return mixed
     */
    protected function getMenuItems()
    {
        return MenuItems::orderBy('ordering', 'asc')->get();
    }
}
