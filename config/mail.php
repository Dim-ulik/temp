<?php

return [

    'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'smtp.mail.ru'),
    'port' => env('MAIL_PORT', 2525),
    'from' => [
        'address' => '4zveta.mailer@mail.ru',
        'name' => 'Заявка с сайта',
    ],
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME', '4zveta.mailer@mail.ru'),
    'password' => env('MAIL_PASSWORD', 'pPY757pxL6ZzfDpjcz2c'),

];

