<?php

return [

    'url' => env('DIGISAC_URL', 'YOU URL'),

    'token' => env('DIGISAC_TOKEN', 'YOU TOKEN'),

    'async_requests' => env('DIGISAC_ASYNC_REQUESTS', false),

    'http_client_handler' => null,

    'headers' => [
        'Content-Type' => 'application/x-www-form-urlencoded',
        'Authorization' => ''
     ]

];
