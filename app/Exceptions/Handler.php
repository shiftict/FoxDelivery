<?php

namespace App\Exceptions;

use Carbon\Carbon;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;
use App\Helpers\Actions;
use Illuminate\Http\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                $request->headers->set('Accept', 'application/json');
                $lang = $request->header('X-localization');

                if (!$lang) {
                    App::setLocale('ar');
                }

                $lang == 'en'
                    ? App::setLocale('en') && Carbon::setLocale('en')
                    : App::setLocale('ar') && Carbon::setLocale('ar');

                return response()->json([
                    'status' => false,
                    'message' => __('api.page_not_found'),
                    'code' => 404,
                    'data' => null,
                ], 404);
            }
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                $request->headers->set('Accept', 'application/json');
                $lang = $request->header('X-localization');

                if (!$lang) {
                    App::setLocale('ar');
                }

                $lang == 'en'
                    ? App::setLocale('en') && Carbon::setLocale('en')
                    : App::setLocale('ar') && Carbon::setLocale('ar');

                return response()->json([
                    'status' => false,
                    'message' => __('api.method_not_allowed') . $request->method(),
                    'code' => 405,
                    'data' => null,
                ], 405);
            }
        });
        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                $request->headers->set('Accept', 'application/json');
                $lang = $request->header('X-localization');

                if (!$lang) {
                    App::setLocale('ar');
                }

                $lang == 'en'
                    ? App::setLocale('en') && Carbon::setLocale('en')
                    : App::setLocale('ar') && Carbon::setLocale('ar');

                return response()->json([
                    'status' => false,
                    'message' => __('auth.unauthorize'),
                    'code' => 401,
                    'data' => null,
                ], 401);
            }
        });
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->wantsJson() || $request->routeIs('*/api/*')
            ? Actions::errorResponse( null, __('api.not_authenticated'),Response::HTTP_UNAUTHORIZED,false)
            : redirect()->guest($exception->guards()[0] == 'admin' ? route('admin.login') : $exception->redirectTo() ?? route('login'));
    }

    protected function invalidJson($request, ValidationException $exception)

    {
        $errors = [];

        foreach ($exception->errors() as $key => $value) {
            $error_obj = collect($value);
            $n = new \stdClass();
            $n->field = $key;
            $n->error = $error_obj->first();
            $errors[] = $n;
        }

        return Actions::errorResponse( compact('errors'), __('api.validation_error'),Response::HTTP_UNPROCESSABLE_ENTITY,false);
    }
}
