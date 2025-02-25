<?php
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

$projectName = 'DragonX';

// Clés de cryptage des données
$encryptionKeys = [
    'prod' => [
        'secretkey' => 'SMZ4}Q@q0IMLo2^p%ZsqCFc$7yd?SOdHE}i~}DijV40J.}JA%+f(*eD|^kma9rW',   // Crypte le mot de passe de la base de données
        'db_secretkey' => 'QoH83t^z]6P3~d$}w_GQM{]]ruM:D3Q-Plkt(y%A)*%XpD53_`2v{-znumcEnC+',  // crype les données de la base de données
    ],
    'pp' => [
        'secretkey' => 'Y}Ni2=fDt=jPDdu=e`wEABdryx7yR6ùe4b|ocP!F%PùrR(Q:wI,A|Mw!P![ocg@', // Crypte le mot de passe de la base de données
        'db_secretkey' => 't,#KnB8!N)=h,B+Bhy$d&E2+D[jDFqsKf.3vnSJY)P!]*EN66)Dz70PGTmsYs@i', // crype les données de la base de données
    ],
    'dev' => [
        'secretkey' => ';CHF~gfcU{pv!4e?5fcixSZ00e_[>qZ+t,Z-n14=6*!n4upkA|qQj]oUa[y`N>)', // Crypte le mot de passe de la base de données
        'db_secretkey' => './6{ORtDkjt?47d|#y3QZ#wjlV#Q^>Sn@+,#Zp8sN@-sR}X(V@4F.mb|eTrSB%_', // crype les données de la base de données
    ]
];


// Mots de passes cryptés
$db_password_crypted = [
    'prod' => 'RcXHgUC59jQm4Hsm+cUbHfh7c9xuxWBt7yPNdt6uJqo=',
    'pp' => 'jzmp0kBhq5x4zC4OmkBWZavcp4CmwhWrcJoDarapNHE=',
    'dev' => '9SLzK16uZaQiGIbahXPOKmgPDjMsWgRGZhzCXkw+428='
];

return [
    // Environment settings
    'environment' => [
        'current' => 'dev', // Options: dev, pp, prod
        'prod' => [     // Production
            'db' => [
                'host' => 'localhost',
                'name' => 'draconis',
                'user' => 'draconis',
                'password' => $db_password_crypted['prod'],
            ],
            'encryption' => [
                'secret_key' => $encryptionKeys['prod'],
            ],
            'debug' => [
                'enabled' => false,
                'log_errors' => true,
                'error_log_path' => __DIR__ . '/logs/prod_error.log',
            ],
            'base_url' => 'https://www.your-production-domain.com',
        ],
        'pp' => [   // pre-production
            'db' => [
                'host' => 'localhost',
                'name' => 'draconis',
                'user' => 'draconis',
                'password' => $db_password_crypted['pp'],
            ],
            'encryption' => [
                'secret_key' => $encryptionKeys['pp'],
            ],
            'debug' => [
                'enabled' => true,
                'log_errors' => true,
                'error_log_path' => __DIR__ . '/logs/preprod_error.log',
            ],
            'base_url' => 'https://preprod.your-domain.com',
        ],
        'dev' => [  // development
            'db' => [
                'host' => 'localhost',
                'name' => 'draconis',
                'user' => 'draconis',
                'password' => $db_password_crypted['dev'],
            ],
            'encryption' => [
                'secret_key' => $encryptionKeys['dev'],
            ],
            'debug' => [
                'enabled' => true,
                'log_errors' => true,
                'error_log_path' => __DIR__ . '/logs/dev_error.log',
            ],
            'base_url' => 'http://localhost/'.$projectName,
        ],
    ],

    // Site configuration
    'site' => [
        'name' => $projectName,
    ],

    // Session settings
    'session' => [
        'name' => $projectName.'_SESSION',
        'lifetime' => 3600, // Session lifetime in seconds
        'path' => '/',
        'secure' => false, // Set to true if using HTTPS
        'httponly' => true, // Prevent JavaScript access to session cookies
    ],

    // API keys or external services
    'services' => [
        'google_api_key' => 'your_google_api_key_here',
        'mail_server' => [
            'host' => 'smtp.mailserver.com',
            'port' => 587,
            'user' => 'mailer@draconis.com',
            'password' => 'secure_password',
        ],
    ],

    // Custom settings
    'custom' => [
        'pagination_limit' => 10, // Number of items per page for pagination
    ],
];
