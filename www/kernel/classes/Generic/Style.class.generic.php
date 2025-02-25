<?php

if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}


class Style
{
    private $cssBasePath; // Chemin de base vers le répertoire CSS
    private $theme;       // Nom du thème à charger
    private $cssFiles = []; // Liste des fichiers CSS collectés

    /**
     * Constructeur : initialise le chemin de base et le thème.
     *
     * @param string $cssBasePath Chemin de base vers le répertoire assets/css/.
     * @param string $theme Nom du thème (ex : 'default').
     */
    public function __construct($theme = 'default')
    {
        $this->cssBasePath = rtrim('assets/templates', '/');
        $this->theme = $theme;
        $this->loadCSSFiles();
    }

    /**
     * Parcourt le répertoire du thème pour collecter les fichiers CSS.
     */
    private function loadCSSFiles()
    {
        $themePath = "{$this->cssBasePath}/{$this->theme}";
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
     * Génère la balise <link> pour un fichier CSS.
     *
     * @param string $file Chemin du fichier CSS.
     * @return string Balise <link>.
     */
    private function generateLinkTag($file)
    {
        return "<link rel=\"stylesheet\" href=\"{$this->cssBasePath}/{$file}\">";
    }

    /**
     * Retourne les balises <link> générées pour tous les fichiers CSS.
     *
     * @return array Tableau des balises <link>.
     */
    public function getCSSLinks()
    {
        $links = [];
        foreach ($this->cssFiles as $file) {
            $links[] = $this->generateLinkTag($file);
        }
        return $links;
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
