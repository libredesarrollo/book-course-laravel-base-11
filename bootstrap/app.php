<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->render(function (NotFoundHttpException $e, Request $request) {
        //     // dd(auth()->user());
        //     if ($request->is('api/*')) {
        //         return response()->json(['message' => '404']);
        //     } else {
        //         return response()->view('errors.NotFoundHttpException');
        //     }
        // });
        // $exceptions->respond(function(Response $response){
        //     dd($response->getStatusCode());
        // });


   

    })->create();
