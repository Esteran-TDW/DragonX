<?php

if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

// Classe des templates
class Templates extends System
{
    private $templateName;
    private $template = [];

    public function __construct()
    {
         parent::__construct();

         if (empty($this->config['template']['name'])) {
            throw new Exception("Aucun template défini dans la configuration.");
        }

        // Récupération du nom du template
        if (!empty($this->config['template'])) {
            $this->setTemplate();
        } else {
            throw new Exception("Aucun template défini dans la configuration.");
        } 
    }

   
    private function setTemplate(){

        if (!empty($this->config['template']['name'])) {
            $this->template['name'] = $this->config['template']['name'];
        } else {
            throw new Exception("Aucun template défini dans la configuration.");
        }

        $this->template['path'] = MAIN_DIRECTORY . DIR_TEMPLATES .'/'. $this->getTemplateName();
        
        if (!is_dir($this->template['path'])) {
            throw new Exception("Le répertoire du template '{$this->templateName}' est introuvable.");
        }

    }
    
    public function getTemplate()
    {
        return $this->template;
    }

    public function getTemplateName()
    {
        return $this->template['name'];
    }
    public function getTemplatePath()
    {
        return $this->template['path'];
    }
    
}
