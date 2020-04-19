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
use App\Models\Oauth;
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
     */
    public function callback()
    {
        if (!$user = Socialite::driver('flickr')->user()) {
            return;
        }

        $model = app(Oauth::class);
        $model->name = 'flickr';

        foreach ($user->accessTokenResponseBody as $key => $value) {
            $model->{$key} = $value;
        }

        $model->save();
    }
}
