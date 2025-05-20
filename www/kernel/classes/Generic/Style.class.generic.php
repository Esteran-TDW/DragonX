<?php

if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

class Style
{
    private $cssBasePath;      // Chemin de base vers le répertoire CSS
    private $theme;            // Nom du thème à charger
    private $cssFiles = [];    // Liste des fichiers CSS collectés
    private $cachePath;        // Répertoire de cache
    private $minifiedCSSFile;  // Fichier CSS minifié
    private $cacheListFile;    // Fichier de cache pour les fichiers CSS

    /**
     * Constructeur : initialise le chemin de base, le thème et le cache.
     *
     * @param string $theme Nom du thème (ex : 'default').
     */
    public function __construct($theme = 'default')
    {    
        global $Templates;
        if (!isset($Templates)) {
            throw new Exception('Templates object not found.');
        }

        $templateName = $Templates->config['template']['name'];
        $this->cssBasePath = rtrim('includes/templates/'.$templateName.'/assets/css', '/');
        $this->theme = $templateName;

        // Répertoire de cache pour le CSS minifié
        $this->cachePath = 'cache/css/';
        $this->minifiedCSSFile = $this->cachePath . '' . $this->theme . '.min.css';
        $this->cacheListFile = $this->cachePath . $this->theme . '.css.cache.php';  // Cache des fichiers CSS pour le thème

        $this->loadCSSFiles();
    }

    /**
     * Parcourt le répertoire du thème pour collecter les fichiers CSS.
     */
    private function loadCSSFiles()
    {
        $themePath = $this->cssBasePath;

        if (!is_dir($themePath)) {
            throw new Exception("Le thème spécifié n'existe pas : $themePath");
        }

        $this->cssFiles = $this->scanDirectory($themePath);
    }

    /**
     * Scanne un répertoire et retourne tous les chemins des fichiers CSS.
     *
     * @param string $directory Chemin du répertoire à scanner.
     * @return array Liste des chemins CSS relatifs.
     */
    private function scanDirectory($directory)
    {
        $cssFiles = [];
        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = "$directory/$file";

            if (is_dir($filePath)) {
                // Appel récursif pour les sous-dossiers
                $cssFiles = array_merge($cssFiles, $this->scanDirectory($filePath));
            } elseif (pathinfo($filePath, PATHINFO_EXTENSION) === 'css') {
                // Ajouter le chemin relatif
                $relativePath = str_replace($this->cssBasePath . '/', '', $filePath);
                $cssFiles[] = $relativePath;
            }
        }

        return $cssFiles;
    }

    /**
     * Vérifie si le cache des fichiers CSS minifiés est valide.
     * Utilise le md5 pour vérifier les modifications.
     *
     * @return bool
     */
    private function isCacheValid()
    {
        // Si le fichier minifié n'existe pas ou si les fichiers CSS sont plus récents que le cache
        if (!file_exists($this->minifiedCSSFile)) {
            return false;
        }

        $cacheTime = filemtime($this->minifiedCSSFile);
        $cachedFiles = file_exists($this->cacheListFile) ? include $this->cacheListFile : [];

        // Vérifier les fichiers CSS et leurs hash (md5)
        foreach ($this->cssFiles as $file) {
            $filePath = $this->cssBasePath . '/' . $file;
            if (!isset($cachedFiles[$file]) || $cachedFiles[$file] !== md5_file($filePath)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Crée un fichier CSS minifié et le place dans le cache.
     */
    private function createMinifiedCSS()
    {
        $minifiedCSS = '';
        $currentFiles = [];

        // Générer le contenu minifié pour chaque fichier CSS
        foreach ($this->cssFiles as $file) {
            $filePath = $this->cssBasePath . '/' . $file;
            $cssContent = file_get_contents($filePath);

            // Simple minification (enlève les espaces et commentaires)
            $cssContent = preg_replace('/\s+/', ' ', $cssContent);  // Réduire les espaces blancs
            $cssContent = preg_replace('/\/\*.*?\*\//', '', $cssContent);  // Supprimer les commentaires

            $minifiedCSS .= $cssContent . "\n";

            // Ajouter le hash md5 pour chaque fichier dans le cache
            $currentFiles[$file] = md5_file($filePath);
        }

        // Créer le répertoire de cache s'il n'existe pas
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, true);
        }

        // Sauvegarder le CSS minifié dans le cache
        file_put_contents($this->minifiedCSSFile, $minifiedCSS);
        file_put_contents($this->cacheListFile, '<?php return ' . var_export($currentFiles, true) . ';');
    }

    /**
     * Retourne la balise <link> pour le fichier CSS minifié.
     *
     * @return string Balise <link>.
     */
    public function getCSSLinks()
    {
        // Si le cache n'est pas valide, régénérer le fichier minifié
        if (!$this->isCacheValid()) {
            $this->createMinifiedCSS();
        }

        // Retourne un seul <link> vers le fichier minifié
        return [
            "<link rel=\"stylesheet\" href=\"{$this->minifiedCSSFile}\">"
        ];
    }

    /**
     * Retourne la liste brute des fichiers CSS (sans balise <link>).
     *
     * @return array Tableau des chemins CSS relatifs.
     */
    public function getCSSFiles()
    {
        return $this->cssFiles;
    }
}
