<?php

class System
{
    public $subDirectories_Class_and_Functions = [
        'classes' => [
            'System',
            'Generic'
        ],
        'functions' => [
            'System',
            'Generic'
        ],
    ];

    public $config;

    public function __construct()
    {

        $this->defineMainDirectory();

        $this->loadConstants();
        $this->autoloadClassesAndFunctions();
        $this->loadConfig();
        $this->startSession();
        $this->handleRoute();
    }


    private function defineMainDirectory()
    {
        defined('MAIN_DIRECTORY') ?: define('MAIN_DIRECTORY', $_SERVER['DOCUMENT_ROOT'] . '/' . $_SERVER['REQUEST_URI']);
    }

    private function loadConstants()
    {
        require_once MAIN_DIRECTORY . '/kernel/constant.php';
    }

    private function autoloadClassesAndFunctions()
    {
        foreach ($this->subDirectories_Class_and_Functions as $type => $directories) {
            foreach ($directories as $directory) {
                $path = MAIN_DIRECTORY . "/kernel/{$type}/{$directory}/";

                if (is_dir($path)) {
                    $files = glob($path . "*.php");
                    foreach ($files as $file) {
                        require_once $file;
                    }
                }
            }
        }
    }

    private function loadConfig()
    {
        // Charger le fichier de configuration
        $configFile = MAIN_DIRECTORY . '/kernel/config.php';

        // Vérifier si le fichier de configuration existe
        if (file_exists($configFile)) {
            $config = include $configFile;

            // Vérifier si la section 'environment' est définie dans la config
            if (isset($config['environment']) && isset($config['environment']['current'])) {
                // Récupérer l'environnement en cours
                $currentEnvironment = $config['environment']['current'];

                // Charger la configuration spécifique à l'environnement
                if (isset($config['environment'][$currentEnvironment])) {
                    // Ajouter 'current' au tableau de config pour y accéder facilement
                    $this->config = $config['environment'][$currentEnvironment];
                    $this->config['current'] = $currentEnvironment; // Ajouter le 'current' à la config

                    // Vérifier si la constante BASE_URL n'est pas déjà définie avant de la définir
                    if (!defined('BASE_URL')) {
                        define('BASE_URL', $this->config['base_url']);
                    }
                } else {
                    throw new Exception("Invalid environment configuration: {$currentEnvironment}");
                }
            } else {
                throw new Exception("Environment configuration not found.");
            }
        } else {
            echo 'Configuration file not found.';
            throw new Exception("Config file not found: $configFile");
        }
    }


    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function handleRoute()
    {
        // Liste des modules autorisés, selon ton projet
        $allowedModules = ['home', 'contact', 'about'];

        // Par défaut, si aucun module n'est précisé
        $module = $_GET['module'] ?? 'home';

        // Sécurité : vérifie que le module fait partie des modules autorisés
        if (!in_array($module, $allowedModules)) {
            $module = 'home'; // Module par défaut
        }

        // Calcul du chemin pour le fichier du module
        $moduleFile = MAIN_DIRECTORY . "/modules/{$module}/{$module}.php";

        // Inclusion du fichier du module
        if (file_exists($moduleFile)) {
            include $moduleFile;
        } else {
            http_response_code(404); // Fichier introuvable
            echo "Module not found.";
        }
    }

}