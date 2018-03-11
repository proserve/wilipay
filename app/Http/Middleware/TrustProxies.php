<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array
     */
    protected $proxies;

    /**
     * The current proxy header mappings.
     *
     * @var array
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
    /* [
            Request::HEADER_FORWARDED => 0b00001, // When using RFC 7239
            Request::HEADER_X_FORWARDED_FOR => 0b00010,
            Request::HEADER_X_FORWARDED_HOST => 0b00100,
            Request::HEADER_X_FORWARDED_PROTO => 0b01000,
            Request::HEADER_X_FORWARDED_PORT => 0b10000,
            Request::HEADER_X_FORWARDED_ALL => 0b11110, // All "X-Forwarded-*" headers
            Request::HEADER_X_FORWARDED_AWS_ELB => 0b11010, // AWS ELB doesn't send X-Forwarded-Host
        ]*/

}
