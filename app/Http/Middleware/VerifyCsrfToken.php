<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
  
    protected $except = [
    '/webhook/twilio',
    'https://2115-154-115-251-98.ngrok-free.app/api/webhook/twilio',
    'api/sim-details', // Ensure you include 'api/' here
    '/send-advertisement-sms',
    'send-message',
    'api/subscribe', // Add this to exclude it from CSRF protection
    ];
}
