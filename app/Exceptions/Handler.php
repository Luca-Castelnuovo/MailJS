<?php

namespace App\Exceptions;

use Exception;

class Handler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        // AuthorizationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Exception $exception
     *
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request   $request
     * @param Exception $exception
     *
     * @return JsonResponse|Response
     */
    public function render($request, Exception $exception)
    {
        // if ($exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException) {
        //     return response()->json(
        //         ['error' => 'endpoint not found'],
        //         404
        //     );
        // }

        return parent::render($request, $exception);
    }
}
