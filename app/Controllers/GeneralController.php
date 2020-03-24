<?php

namespace App\Controllers;

use Zend\Diactoros\ServerRequest;

use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\TextResponse;

class GeneralController extends Controller
{
    /**
     * Return required params for clients.
     *
     * @return JsonResponse
     */
    public function index(ServerRequest $request)
    {
        // return new JsonResponse($this->getOrigin($request));
        return new HtmlResponse('<html>This is also an HTML response</html>', 200);
    }
}
