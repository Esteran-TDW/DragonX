<?php

if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

class Security extends System
{
    private $env;                  // Environnement actuel
    private $encryptionMethod;     // Méthode de cryptage (ex : aes-256-cbc)
    private $secretkey;            // Clé secrète

    public function __construct()
    {
        parent::__construct();

        $this->encryptionMethod = 'aes-256-cbc';
        $this->env = $this->config['current'];

        // Vérifie si la clé de cryptage existe
        if (!isset($this->config['encryption']['secret_key']['secretkey'])) {
            throw new Exception('Clé de cryptage manquante pour l\'environnement : ' . $this->env);
        }

        $this->secretkey = $this->config['encryption']['secret_key']['secretkey'];
    }

    /**
     * Crypte une donnée avec une clé définie dans la configuration.
     *
     * @param string $data Les données à crypter.
     * @return string Le texte crypté encodé en base64.
     */
    public function encrypt($data)
    {
        if (empty($data)) {
            throw new InvalidArgumentException('Les données à crypter ne peuvent pas être vides.');
        }

        return openssl_encrypt($data, $this->encryptionMethod, $this->secretkey);
    }

    /**
     * Décrypte une donnée cryptée avec une clé définie dans la configuration.
     *
     * @param string $encryptedData Les données cryptées.
     * @return string|null Les données en clair ou null en cas d'échec.
     */
    public function decrypt($encryptedData)
    {
        if (empty($encryptedData)) {
            throw new InvalidArgumentException('Les données cryptées ne peuvent pas être vides.');
        }

        $decrypted = openssl_decrypt($encryptedData, $this->encryptionMethod, $this->secretkey);

        if ($decrypted === false) {
            throw new RuntimeException('Le décryptage a échoué. Les données ou la clé peuvent être incorrectes.');
        }

        return $decrypted;
    }

    /**
     * Vérifie si une donnée brute correspond à une donnée cryptée.
     *
     * @param string $data Les données brutes à vérifier.
     * @param string $encryptedData Les données cryptées à vérifier.
     * @return bool True si elles correspondent, False sinon.
     */
    public function verify($data, $encryptedData)
    {
        return $this->encrypt($data) === $encryptedData;
    }
}
