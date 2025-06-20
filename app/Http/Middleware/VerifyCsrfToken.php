<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Daftar URL yang tidak kena CSRF
     */
    protected $except = [
        '/klien/midtrans/webhook',
    ];
}
