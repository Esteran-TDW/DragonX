#!/bin/bash

# Répertoire racine du projet
base_dir=~/Developpement/www/DraconisCMS

# Créer le répertoire principal
mkdir -p "$base_dir"

# Créer la structure des sous-répertoires pour /assets
mkdir -p "$base_dir/assets/css" \
         "$base_dir/assets/js" \
         "$base_dir/assets/img" \
         "$base_dir/assets/img/logos/main" \
         "$base_dir/assets/img/logos/partners" \
         "$base_dir/assets/img/logos/social" \
         "$base_dir/assets/img/banners" \
         "$base_dir/assets/img/products" \
         "$base_dir/assets/img/misc" \
         "$base_dir/assets/img/icons" \
         "$base_dir/assets/img/responsive/logos" \
         "$base_dir/assets/img/responsive/banners" \
         "$base_dir/assets/ajax" \
	  "$base_dir/assets/libs" \
         "$base_dir/assets/media/videos" \
         "$base_dir/assets/media/audio"

# Créer la structure des sous-répertoires pour /includes
mkdir -p "$base_dir/includes" \
	 "$base_dir/classes" \
	 "$base_dir/functions"

# Créer le répertoire des modules
mkdir -p "$base_dir/modules"

# Créer le répertoire de l'api accessible pour les utilisateurs externes
mkdir -p "$base_dir/api"

# Créer la structure du répertoire /admin
mkdir -p "$base_dir/admin/assets/css" \
         "$base_dir/admin/assets/js" \
         "$base_dir/admin/assets/img" \
         "$base_dir/admin/assets/img/logos/main" \
         "$base_dir/admin/assets/img/logos/partners" \
         "$base_dir/admin/assets/img/logos/social" \
         "$base_dir/admin/assets/img/banners" \
         "$base_dir/admin/assets/img/products" \
         "$base_dir/admin/assets/img/misc" \
         "$base_dir/admin/assets/img/icons" \
         "$base_dir/admin/assets/img/responsive/logos" \
         "$base_dir/admin/assets/img/responsive/banners" \
         "$base_dir/admin/assets/ajax" \
         "$base_dir/admin/assets/media/videos" \
         "$base_dir/admin/assets/media/audio" \
         "$base_dir/admin/includes" \
         "$base_dir/log" \
         "$base_dir/kernel" \
         "$base_dir/kernel/api"   # API inaccessible pour les utilisateurs externes, contient les routes de l'API
         "$base_dir/kernel/assets/" \
         "$base_dir/kernel/assets/js" \
         "$base_dir/kernel/assets/ajax" \
         "$base_dir/kernel/classes" \
         "$base_dir/kernel/classes/System" \
         "$base_dir/kernel/classes/Generic" \
         "$base_dir/kernel/functions" \
         "$base_dir/kernel/functions/System" \
         "$base_dir/kernel/functions/Generic" \
	   "$base_dir/includes/classes" \
         "$base_dir/includes/classes/Specific" \
         "$base_dir/includes/functions" \
         "$base_dir/includes/functions/Specific" \
         "$base_dir/includes/jobs" \
         "$base_dir/includes/cron" \
         "$base_dir/includes/workers" \
         "$base_dir/includes/queue" \
         "$base_dir/includes/locales" \
         "$base_dir/includes/templates" \
         "$base_dir/includes/templates/header" \
         "$base_dir/includes/templates/footer" \
	 "$base_dir/admin/classes" \
         "$base_dir/admin/classes/System" \
         "$base_dir/admin/classes/Generic" \
         "$base_dir/admin/classes/Specific" \
         "$base_dir/admin/sections"

# Créer le répertoire /uploads et ses sous-dossiers
mkdir -p "$base_dir/uploads/images" \
         "$base_dir/uploads/documents" \
         "$base_dir/uploads/media/videos" \
         "$base_dir/uploads/media/audio"

# Créer le fichier index.php et .htaccess à la racine
touch "$base_dir/index.php" \
      "$base_dir/.htaccess"

# Créer le fichier admin.php dans /admin
touch "$base_dir/admin/admin.php"

echo "La structure de répertoires a été créée avec succès dans $base_dir"
