<?php

namespace Ghi\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Ghi\Core\Exceptions\ReglaNegocioException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->wantsJson() or $request->ajax()) {
            if ($e instanceof ReglaNegocioException) {
                return new JsonResponse([$e->getMessage()], 422);
            }

            return new JsonResponse(['error', get_class($e), $e->getMessage()], 500);
        }

        if ($e instanceof ModelNotFoundException) {
            flash()->error('El recurso buscado no fue encontrado.');
            return back();
        } elseif ($e instanceof ReglaNegocioException) {
            flash()->error($e->getMessage());
            return back();
        }

        return parent::render($request, $e);
    }
}
