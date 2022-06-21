<?php

return [
    'token_name' => 'API Token',
    'cookie_name' => env('COOKIE_NAME', '_ses_tkn'),
    'cookie_expiry_min' => env('COOKIE_EXPR_MIN', 4320), // 3 days
];