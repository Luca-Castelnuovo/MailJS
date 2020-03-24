<?php

namespace App\Controllers;

use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response\JsonResponse;

class SubmissionController extends Controller
{
    /**
     * Handle submission in JSON format.
     *
     * @return JsonResponse
     */
    public function json()
    {
        return new JsonResponse(['status' => true]);
    }

    /**
     * Handle submission in JSON format.
     *
     * @return JsonResponse
     */
    public function form()
    {
        return new JsonResponse(['status' => true]);
    }
}
