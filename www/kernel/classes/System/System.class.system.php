<?php

class System
{

    public $subDirectories_Class = [
        'kernel' => [
            'classes' => [
                'System',
                'Generic'
            ],
        ],
        'includes' => [
            'classes' => [
                'Specific',
            ],
        ],
    ];

    public $subDirectories_Functions = [
        'kernel' => [
            'functions' => [
                'System',
                'Generic'
            ],
        ],
        'includes' => [
            'functions' => [
                'Specific',
            ],
        ],
    ];

    public $priorityClasses = [
            'Security',
            'Database',
            'Templates'
        ];

    public $config = [];
    public $sessionId;
    public $instance;



    public function __construct()
    {
        $this->startSession();
        $this->handleRoute(); 
    }

      private function startSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Si l'ID de session n'existe pas encore, on le crée
    if (!isset($_SESSION['id'])) {
        $_SESSION['id'] = uniqid();
        $this->sessionId = $_SESSION['id'];    
    }

     if (!isset($this->sessionId)) {
        $this->sessionId = $_SESSION['id'];    
    }

    $this->initializeSystem();
}

    private function initializeSystem()
    {
         $class = static::class;

 
        if((($class == 'System') ) && (!isset($this->instance['System'])) || (!isset($this->instance['System']['initialized']))) {
            
            $this->defineMainDirectory();
            $this->loadConstants();
            $this->loadConfig();
            $this->instance[$class]['initialized'] = true;
            $_SESSION['instance'][$class]['initialized'] = true;
            
        } elseif ( ($class != 'System')  && (!isset($this->instance[$class])) || (!isset($this->instance[$class]['initialized']))) {
            echo '<br>Création de l\'instance '. $class . '<br>';
            $$class = new $class();
            $this->instance[$class]['initialized'] = true;
            $_SESSION['instance'][$class]['initialized'] = true;
        } else {
            echo 'Instance '. $class .' déjà existante'. PHP_EOL;

        }
    }


    private function defineMainDirectory()
    {
        if (!function_exists('findProjectRoot')) {
        function findProjectRoot($startDir = __DIR__) {
            // On définit un fichier ou un répertoire à partir duquel on peut identifier la racine du projet
            $markerFiles = ['kernel'];  // Ajouter ici des fichiers/dossiers uniques à ton projet
        
            // Remonter dans les répertoires jusqu'à trouver l'un des fichiers ou dossiers marqués
            $dir = realpath($startDir);
            while ($dir !== false) {
                foreach ($markerFiles as $marker) {
                    if (file_exists($dir . '/' . $marker)) {
                        return $dir; // Retourne le répertoire racine du projet
                    }
                }
                // Remonter d'un niveau dans l'arborescence
                $dir = dirname($dir);
            }
        
            return false; // Retourne false si aucun répertoire racine n'a été trouvé
        }
    }

        // Définir automatiquement le répertoire racine du projet
        $projectRoot = findProjectRoot();
        
        // Vérifier si la racine du projet a été trouvée et définir MAIN_DIRECTORY
        if ($projectRoot !== false) {
            defined('MAIN_DIRECTORY') ?: define('MAIN_DIRECTORY', $projectRoot .'/');
        } else {
            echo "Impossible de trouver le répertoire racine du projet.";
        }
    }

    private function loadConstants()
    {
        require_once MAIN_DIRECTORY . '/kernel/constant.php';
    }

    public function autoloadFilesClasses()
    {
        foreach ($this->subDirectories_Class as $arch => $types) {
            foreach($types as $type => $directories) {
                foreach ($directories as $directory) {
                    $path = MAIN_DIRECTORY . $arch.'/'.$type.'/'.$directory.'/';
                    if (!is_dir($path)) {
                        throw new Exception("Le répertoire n'existe pas : $path");
                    } else {
                    $filteredFiles = array_filter(glob($path . '*.class.' . strtolower($directory) . '.php'), function ($file) {
                        return strpos($file, 'System.class.system') === false; // Exclut les fichiers contenant "System.class.system"
                    });

                        if (!empty($filteredFiles)) {
                            $files[] = $filteredFiles;
                        }
                    }
                }
            }
        }

        // Fonction de tri pour réordonner le tableau $files en fonction de $this->priorityClasses
        $priorityClasses = $this->priorityClasses;

        $sortFilesByPriority = function (&$files) use ($priorityClasses) {
            usort($files, function ($a, $b) use ($priorityClasses) {
                // Extraire uniquement le nom de la classe en retirant tout le chemin et l'extension
                $classA = preg_replace('/\.class\..+\.php$/', '', basename($a));
                $classB = preg_replace('/\.class\..+\.php$/', '', basename($b));

                // Trouver l'index des classes dans $this->priorityClasses, si elles existent
                $indexA = array_search($classA, $this->priorityClasses);
                $indexB = array_search($classB, $this->priorityClasses);

                // Si les deux classes sont dans la liste des priorités, les comparer par index
                if ($indexA !== false && $indexB !== false) {
                    return $indexA - $indexB;
                }

                // Si seule la classe A est prioritaire, elle passe avant
                if ($indexA !== false) {
                    return -1;
                }

                // Si seule la classe B est prioritaire, elle passe avant
                if ($indexB !== false) {
                    return 1;
                }

                // Sinon, tri par ordre alphabétique
                return strcmp($classA, $classB);
            });
        };
        
        

        // Appliquer le tri à chaque sous-tableau
        foreach ($files as &$subArray) {
            $sortFilesByPriority($subArray, $this->priorityClasses);
        }

        foreach ($files as $keyes => $keys) {
           
            foreach ($keys as $key => $file) {

                require_once $file;

                $filename = basename($file);

                // Utiliser preg_replace pour retirer les parties indésirables du nom du fichier
                $classNames[] = preg_replace('/\.class\..+\.php$/', '', $filename);
            }
        }
  
        return $classNames;

    }
                 
        public function autoloadFilesFunctions()
    {
        foreach ($this->subDirectories_Functions as $arch => $types) {
            foreach($types as $type => $directories) {
                foreach ($directories as $directory) {
                    $path = MAIN_DIRECTORY . $arch.'/'.$type.'/'.$directory.'/';
                    
                    if (!is_dir($path)) {
                        throw new Exception("Le répertoire n'existe pas : $path");
                    } else {
                    $filteredFiles = array_filter(glob($path . '*.class.' . strtolower($directory) . '.php'), function ($file) {
                        return strpos($file, 'System.class.system') === false; // Exclut les fichiers contenant "System.class.system"
                    });

                        if (!empty($filteredFiles)) {
                            $files[] = $filteredFiles;
                        }
                    }
                }
            }
        }


        foreach ($files as $keyes => $keys) {
           
            foreach ($keys as $key => $file) {

                require_once $file;


            }
        }
  
  

    }
                    

    public function loadConfig()
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

                // Vérifie la définition du template
                if (isset($config['template'])) {
                    $this->config['template'] = $config['template'];
                } else {
                    throw new Exception("Template configuration not found.");
                }

            } else {
                throw new Exception("Environment configuration not found.");
            }
        } else {
            echo 'Configuration file not found.';
            throw new Exception("Config file not found: $configFile");
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