<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    $path = public_path('index.html');

    if (File::exists($path)) {
        return Response::file($path);
    }

    abort(404);
})->where('any', '.*');

