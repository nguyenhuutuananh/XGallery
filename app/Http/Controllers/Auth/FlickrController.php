<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\OAuth\Flickr;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class FlickrController
 * @package App\Http\Controllers\Auth
 */
class FlickrController extends BaseController
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return RedirectResponse
     */
    public function login()
    {
        return Socialite::driver('flickr')->with(['perms' => 'read, write, or delete'])->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return void
     */
    public function callback()
    {
        $user = Socialite::driver('flickr')->user();
        dd($user);
    }

    public function user()
    {
        $flickr = new Flickr();
        $res    = $flickr->get(['method' => 'flickr.contacts.getList']);

        dd($res);
    }
}
