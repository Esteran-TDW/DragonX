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

    public $subDirectories_JS = [
        'kernel' => [
            'assets' => [
                'js',
            ],
        ],
    ];

    public $subDirectories_CSS = [
        'assets' => [
                'css',
        ],
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
            defined('MAIN_DIRECTORY') ?: define('MAIN_DIRECTORY', $projectRoot);
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
    $cacheFile = MAIN_DIRECTORY . '/cache/main/main.classes.cache.php';
    $classNames = [];
    $files = [];

    $cacheFile = MAIN_DIRECTORY . '/cache/main/main.classes.cache.php';
    $cacheDir = dirname($cacheFile); // Récupère le chemin du répertoire contenant le fichier

    // Vérifie si le répertoire existe, sinon le crée
    if (!@mkdir($cacheDir, 0755, true) && !is_dir($cacheDir)) {
        throw new Exception("Impossible de créer le répertoire de cache : $cacheDir");
    }

    // Chargement du cache existant
    $cachedFiles = file_exists($cacheFile) ? include $cacheFile : [];

    // Fichier temporaire pour reconstruire le cache
    $currentFiles = [];

    foreach ($this->subDirectories_Class as $arch => $types) {
        foreach ($types as $type => $directories) {
            foreach ($directories as $directory) {
                $path = MAIN_DIRECTORY . '/' . $arch . '/' . $type . '/' . $directory . '/';
                if (!is_dir($path)) {
                    throw new Exception("Le répertoire n'existe pas : $path");
                }

                $foundFiles = array_filter(
                    glob($path . '*.class.' . strtolower($directory) . '.php'),
                    fn($file) => strpos($file, 'System.class.system') === false
                );

                foreach ($foundFiles as $file) {
                    $currentFiles[$file] = md5_file($file);
                }
            }
        }
    }

    // Vérification des différences entre cache et état actuel
    $rebuildCache = false;
    $rebuildCache = (count($cachedFiles) !== count($currentFiles)) || array_diff_assoc($currentFiles, $cachedFiles);

    // Mise à jour du cache si nécessaire
    if ($rebuildCache) {
        file_put_contents($cacheFile, '<?php return ' . var_export($currentFiles, true) . ';');
        $files = array_keys($currentFiles);
    } else {
        $files = array_keys($cachedFiles);
    }

    // Tri selon priorité
    $priorityClasses = $this->priorityClasses;

    usort($files, function ($a, $b) use ($priorityClasses) {
        $classA = preg_replace('/\.class\..+\.php$/', '', basename($a));
        $classB = preg_replace('/\.class\..+\.php$/', '', basename($b));

        $indexA = array_search($classA, $priorityClasses);
        $indexB = array_search($classB, $priorityClasses);

        if ($indexA !== false && $indexB !== false) return $indexA - $indexB;
        if ($indexA !== false) return -1;
        if ($indexB !== false) return 1;

        return strcmp($classA, $classB);
    });

    // Inclusion des fichiers et création des noms de classe
    foreach ($files as $file) {
        require_once $file;
        $filename = basename($file);
        $classNames[] = preg_replace('/\.class\..+\.php$/', '', $filename);
    }

    return $classNames;
}
    
        /**
        * Autoload des fichiers de fonctions
        * @return void
        */
public function autoloadFilesFunctions()
{
    $cacheFile = MAIN_DIRECTORY . '/cache/main/main.functions.cache.php';
    $currentFiles = [];
    $cachedFiles = file_exists($cacheFile) ? include $cacheFile : [];

    // Scan des fichiers .func.[type].php dans les bons dossiers
    foreach ($this->subDirectories_Functions as $arch => $types) {
        foreach ($types as $type => $directories) {
            foreach ($directories as $directory) {
                $path = MAIN_DIRECTORY . '/' . $arch . '/' . $type . '/' . $directory . '/';
                if (!is_dir($path)) {
                    throw new Exception("Le répertoire n'existe pas : $path");
                }

                $foundFiles = glob($path . '*.func.' . strtolower($directory) . '.php');
                foreach ($foundFiles as $file) {
                    $currentFiles[$file] = md5_file($file);
                }
            }
        }
    }

    // Comparaison avec le cache
    $rebuildCache = false;
    $rebuildCache = (count($cachedFiles) !== count($currentFiles)) || array_diff_assoc($currentFiles, $cachedFiles);


    // Mise à jour du cache si besoin
    if ($rebuildCache) {
        file_put_contents($cacheFile, '<?php return ' . var_export($currentFiles, true) . ';');
        $files = array_keys($currentFiles);
    } else {
        $files = array_keys($cachedFiles);
    }

    // Inclusion des fichiers
    foreach ($files as $file) {
        require_once $file;
    }
}

public function autoloadJS()
{
    $cacheListFile = MAIN_DIRECTORY . '/cache/main/main.js.cache.php';
    $cacheMinifiedFile = MAIN_DIRECTORY . '/cache/main/main.min.js';
    $files = [];

    // Chargement du cache existant
    $cachedFiles = file_exists($cacheListFile) ? include $cacheListFile : [];

    // Liste actuelle
    $currentFiles = [];

    foreach ($this->subDirectories_JS as $arch => $types) {
        foreach ($types as $type => $directories) {
            foreach ($directories as $directory) {
                $path = MAIN_DIRECTORY . '/' . $arch . '/' . $type . '/' . $directory . '/';
                if (!is_dir($path)) {
                    throw new Exception("Le répertoire n'existe pas : $path");
                }

                $jsFiles = glob($path . '*.js');
                foreach ($jsFiles as $file) {
                    $currentFiles[$file] = md5_file($file);
                }
            }
        }
    }

    // Vérifier si le cache est à jour ou si le fichier minifié est manquant
    $rebuildCache = !file_exists($cacheMinifiedFile); 
    $rebuildCache = (count($cachedFiles) !== count($currentFiles)) || array_diff_assoc($currentFiles, $cachedFiles);


    // Recréation du fichier concaténé si besoin
    if ($rebuildCache) {
        $combined = '';
        foreach (array_keys($currentFiles) as $file) {
            $content = file_get_contents($file);
            $combined .= "\n/* === " . basename($file) . " === */\n" . $content;
        }
        // Optionnel : minification simplifiée (peut être remplacée par une lib)
        $combined = preg_replace('/\/\/.*$/m', '', $combined); // retire les commentaires //
        $combined = preg_replace('/\s+/', ' ', $combined);     // retire les espaces excessifs
        $combined = trim($combined);

        file_put_contents($cacheMinifiedFile, $combined);
        file_put_contents($cacheListFile, '<?php return ' . var_export($currentFiles, true) . ';');
    }

    // Inclure le fichier unique dans la page
    $relativePath = str_replace(MAIN_DIRECTORY . '/', '', $cacheMinifiedFile);
    echo '<script src="' . htmlspecialchars($relativePath) . '?v=' . md5_file($cacheMinifiedFile) . '"></script>' . PHP_EOL;
}

       
/* public function autoloadCSS()
{
    $files = [];

    foreach ($this->subDirectories_CSS as $arch => $types) {
        foreach ($types as $directory) {
                $path = MAIN_DIRECTORY . '/' . $arch . '/' . $directory . '/';
                if (!is_dir($path)) {
                    throw new Exception("Le répertoire n'existe pas : $path");
                } else {
                    // Rechercher tous les fichiers .js dans le répertoire
                    $filesInDirectory = glob($path . '*.css');
                    if (!empty($filesInDirectory)) {
                        $files = array_merge($files, $filesInDirectory);
                    }
                }
        }
    }

    // Générer les balises <script> pour chaque fichier JS
    foreach ($files as $file) {
        // Convertir le chemin absolu en chemin relatif pour le navigateur
        $relativePath = str_replace(MAIN_DIRECTORY . '/', '', $file);
        echo '<link rel="stylesheet" href="' . htmlspecialchars($relativePath) . '">' . PHP_EOL;
    }
} */

/* public function autoloadCSS()
{
    $cacheFile = MAIN_DIRECTORY . '/cache/main/main.css.cache.php';
    $files = [];
    $currentFiles = [];

    // Chargement du cache existant
    $cachedFiles = file_exists($cacheFile) ? include $cacheFile : [];

    foreach ($this->subDirectories_CSS as $arch => $directories) {
        foreach ($directories as $directory) {
            $path = MAIN_DIRECTORY . '/' . $arch . '/' . $directory . '/';
            if (!is_dir($path)) {
                throw new Exception("Le répertoire n'existe pas : $path");
            }

            $foundFiles = glob($path . '*.css');
            foreach ($foundFiles as $file) {
                $currentFiles[$file] = md5_file($file);
            }
        }
    }

    // Vérifier si le cache est à jour
    $rebuildCache = false;

    foreach ($currentFiles as $file => $hash) {
        if (!isset($cachedFiles[$file]) || $cachedFiles[$file] !== $hash) {
            $rebuildCache = true;
            break;
        }
    }

    if (!$rebuildCache && count($cachedFiles) !== count($currentFiles)) {
        $rebuildCache = true;
    }

    // Mettre à jour le cache si nécessaire
    if ($rebuildCache) {
        file_put_contents($cacheFile, '<?php return ' . var_export($currentFiles, true) . ';');
        $files = array_keys($currentFiles);
    } else {
        $files = array_keys($cachedFiles);
    }

    // Générer les balises <link> pour chaque fichier CSS
    foreach ($files as $file) {
        $relativePath = str_replace(MAIN_DIRECTORY . '/', '', $file);
        echo '<link rel="stylesheet" href="' . htmlspecialchars($relativePath) . '">' . PHP_EOL;
    }
} */

public function autoloadCSS()
{
    $cacheListFile = MAIN_DIRECTORY . '/cache/main/main.css.cache.php';
    $cacheMinifiedFile = MAIN_DIRECTORY . '/cache/main/main.min.css';
    $files = [];

    // Chargement du cache existant
    $cachedFiles = file_exists($cacheListFile) ? include $cacheListFile : [];

    // Liste actuelle
    $currentFiles = [];

    foreach ($this->subDirectories_CSS as $arch => $directories) {
        foreach ($directories as $directory) {
            $path = MAIN_DIRECTORY . '/' . $arch . '/' . $directory . '/';
            if (!is_dir($path)) {
                throw new Exception("Le répertoire n'existe pas : $path");
            }

            $cssFiles = glob($path . '*.css');
            foreach ($cssFiles as $file) {
                $currentFiles[$file] = md5_file($file);
            }
        }
    }

    // Vérifier si le cache est à jour ou si le fichier minifié est manquant
    $rebuildCache = !file_exists($cacheMinifiedFile); 
    $rebuildCache = (count($cachedFiles) !== count($currentFiles)) || array_diff_assoc($currentFiles, $cachedFiles);


    // Recréation du fichier concaténé si besoin
    if ($rebuildCache) {
        $combined = '';
        foreach (array_keys($currentFiles) as $file) {
            $content = file_get_contents($file);
            $combined .= "\n/* === " . basename($file) . " === */\n" . $content;
        }

        // Minification simple : retirer commentaires et espaces
        $combined = preg_replace('!/\*.*?\*/!s', '', $combined); // commentaires /* */
        $combined = preg_replace('/\s+/', ' ', $combined);       // espaces
        $combined = preg_replace('/\s*([{}:;,])\s*/', '$1', $combined); // espaces autour des symboles
        $combined = trim($combined);

        file_put_contents($cacheMinifiedFile, $combined);
        file_put_contents($cacheListFile, '<?php return ' . var_export($currentFiles, true) . ';');
    }

    // Inclure le fichier unique dans la page
    $relativePath = str_replace(MAIN_DIRECTORY . '/', '', $cacheMinifiedFile);
    echo '<link rel="stylesheet" href="' . htmlspecialchars($relativePath) . '?v=' . md5_file($cacheMinifiedFile) . '">' . PHP_EOL;
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