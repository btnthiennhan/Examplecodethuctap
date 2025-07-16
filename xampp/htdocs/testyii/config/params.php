<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'momo' => [
        'partner_code' => $_ENV['MOMO_PARTNER_CODE'] ?? '',
        'access_key' => $_ENV['MOMO_ACCESS_KEY'] ?? '',
        'secret_key' => $_ENV['MOMO_SECRET_KEY'] ?? '',
        'api_url' => $_ENV['MOMO_API_URL'] ?? '',
        'return_url' => $_ENV['MOMO_RETURN_URL'] ?? '',
        'notify_url' => $_ENV['MOMO_NOTIFY_URL'] ?? '',
        'request_type' => $_ENV['MOMO_REQUEST_TYPE'] ?? '',
        'deeplink_callback' => $_ENV['MOMO_DEEPLINK_CALLBACK'] ?? '',
    ],
];
