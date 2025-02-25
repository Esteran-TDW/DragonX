<?php
/*
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}
*/

// TODO : Faire remonter les $encryptionKeys depuis la config.php

// Tableau des clés secrètes pour chaque environnement
$encryptionKeys = [
    'prod' => [
        'secretkey' => 'SMZ4}Q@q0IMLo2^p%ZsqCFc$7yd?SOdHE}i~}DijV40J.}JA%+f(*eD|^kma9rW',
        'db_secretkey' => 'QoH83t^z]6P3~d$}w_GQM{]]ruM:D3Q-Plkt(y%A)*%XpD53_`2v{-znumcEnC+',  // Peut-être générer une clé DB séparée pour la base de données
    ],
    'pp' => [
        'secretkey' => 'Y}Ni2=fDt=jPDdu=e`wEABdryx7yR6ùe4b|ocP!F%PùrR(Q:wI,A|Mw!P![ocg@',
        'db_secretkey' => 't,#KnB8!N)=h,B+Bhy$d&E2+D[jDFqsKf.3vnSJY)P!]*EN66)Dz70PGTmsYs@i',
    ],
    'dev' => [
        'secretkey' => ';CHF~gfcU{pv!4e?5fcixSZ00e_[>qZ+t,Z-n14=6*!n4upkA|qQj]oUa[y`N>)',
        'db_secretkey' => './6{ORtDkjt?47d|#y3QZ#wjlV#Q^>Sn@+,#Zp8sN@-sR}X(V@4F.mb|eTrSB%_',
    ]
];

$db_password = [
    'production' => 'aU3v-t3/I@0rjLp]',
    'pp' => 'aU3v-t3/I@0rjLp]',
    'dev' => 'aU3v-t3/I@0rjLp]'
];

// Fonction pour chiffrer le texte
function encrypt($input, $key)
{
    $output = '';
    // Applique XOR entre chaque caractère et la clé
    for ($i = 0; $i < strlen($input); $i++) {
        $output .= $input[$i] ^ $key[$i % strlen($key)];
    }
    return $output;
}


$db_password_crypted = [
    'production' => openssl_encrypt($db_password['production'], 'aes-256-cbc', $encryptionKeys['production']['secretkey']),
    'pp' => openssl_encrypt($db_password['pp'], 'aes-256-cbc', $encryptionKeys['pp']['secretkey']),
    'dev' => openssl_encrypt($db_password['dev'], 'aes-256-cbc', $encryptionKeys['dev']['secretkey'])
];


//print_r($db_password['production']);
// Affichage des mots de passe cryptés
echo '<pre>';
print_r($db_password_crypted);
echo '</pre>';

foreach ($db_password_crypted as $key => $value) {
    echo $decrypted_text = openssl_decrypt($value, 'aes-256-cbc', $encryptionKeys['production']['secretkey']);
}


