<?php

namespace App\Controllers;

use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response\JsonResponse;

class TemplateController extends Controller
{
    /**
     * Handle submission in JSON format.
     *
     * @return JsonResponse
     */
    public function create()
    {
        // check if user owns template
        return new JsonResponse(['create' => true]);
    }

    /**
     * Handle submission in JSON format.
     *
     * @return JsonResponse
     */
    public function view($id)
    {
        // check if user owns template
        return new JsonResponse(['view' => $id]);
    }

    /**
     * Handle submission in JSON format.
     *
     * @return JsonResponse
     */
    public function update($id)
    {
        // check if user owns template
        return new JsonResponse(['update' => $id]);
    }


    /**
     * Handle submission in JSON format.
     *
     * @return JsonResponse
     */
    public function delete($id)
    {
        // check if user owns template
        return new JsonResponse(['delete' => $id]);
    }
}
