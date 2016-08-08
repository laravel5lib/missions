<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |

     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */
    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    'allowedHeaders' => ['Origin', 'Content-Type', 'Authorization', 'X-Requested-With'],
    'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE'],
    'exposedHeaders' => ['Authorization'],
    'maxAge' => 0,
    'hosts' => [],
];
