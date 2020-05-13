<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends BaseController
{
    private array $response = [
        'data' => null,
        'succeed' => true,
        'message' => null
    ];

    /**
     * @param  null  $data
     * @return Response
     */
    public function apiSucceed($data = null)
    {
        return $this->respondSucceed(response()->json($this->getResponse($data)));
    }

    /**
     * @param $data
     * @param  bool  $isSucceed
     * @return array
     */
    private function getResponse($data, $isSucceed = true): array
    {
        $this->response['data'] = $data;
        $this->response['succeed'] = $isSucceed;
        return $this->response;
    }
}
