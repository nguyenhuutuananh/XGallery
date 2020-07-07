<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers;

use App\Http\Traits\HasMenu;
use App\Http\Traits\HasModel;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 * @package App\Http\Controllers
 */
class BaseController extends Controller
{
    use HasMenu;
    use HasModel;

    /**
     * @param  Response  $response
     * @return Response
     */
    protected function respondSucceed(Response $response)
    {
        return $response->setStatusCode(Response::HTTP_OK);
    }
}
