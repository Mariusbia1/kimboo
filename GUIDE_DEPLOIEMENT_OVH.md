# Guide Complet : Déploiement OVH via SSH & Mise à Jour de la Base de Données

Ce guide vous explique pas à pas comment vous connecter à votre serveur OVH en SSH, mettre à jour le code source depuis GitHub et appliquer les modifications sur votre base de données MySQL.

---

## 1. Connexion SSH à votre serveur OVH

### A. Récupérer vos identifiants OVH
1. Rendez-vous sur votre espace client **[OVHcloud](https://www.ovh.com/manager/)**.
2. Allez dans la section **Web Cloud** > **Hébergements** > Cliquez sur votre domaine / hébergement.
3. Cliquez sur l'onglet **FTP - SSH** :
   - Notez votre **Serveur SSH / FTP** (ex: `ssh.cluster030.hosting.ovh.net` ou `ftp.mondomaine.com`).
   - Notez votre **Nom d'utilisateur SSH** (ex: `kimboozk`).
   - Vérifiez que la colonne **SSH** est bien activée (**Actif**). Si non, cliquez sur les 3 points à droite > *Modifier* > Activer le SSH.

### B. Se connecter depuis votre terminal (Mac / Linux / Windows PowerShell)
Ouvrez votre terminal et tapez la commande suivante (en remplaçant par vos vrais identifiants) :

```bash
ssh VOTRE_UTILISATEUR@ssh.clusterXXX.hosting.ovh.net
```
*(Si vous avez un VPS OVH dédié : `ssh debian@IP_DE_VOTRE_VPS` ou `ssh ubuntu@IP_DE_VOTRE_VPS`)*

1. Appuyez sur **Entrée**.
2. Si le terminal vous demande `Are you sure you want to continue connecting (yes/no)?`, tapez `yes`.
3. Entrez votre mot de passe (les caractères ne s'affichent pas à l'écran par sécurité, c'est normal), puis validez avec **Entrée**.

---

## 2. Naviguer dans le dossier du projet

Une fois connecté sur le serveur :

```bash
# Aller dans le dossier web public
cd www

# (Ou si votre projet est dans un sous-dossier, par exemple cd ~/kimboo)
# Vérifier où vous êtes :
pwd

# Voir les fichiers :
ls -la
```

---

## 3. Mettre à jour le code du site (Git Pull)

Lorsque vous avez poussé vos modifications depuis votre ordinateur avec `git push origin main`, exécutez sur OVH :

```bash
# 1. Récupérer la dernière version du code
git pull origin main

# 2. Vider les anciens caches Laravel pour appliquer les changements immédiatement
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear

# 3. Optimiser les performances
php artisan optimize
```

---

## 4. Mettre à jour la Base de Données (BD)

Selon le type de modification apportée à la base de données, suivez l'une des méthodes ci-dessous :

---

### Cas 1 : Nouvelles tables ou colonnes créées via Laravel (Migrations)
Si vous avez ajouté de nouvelles migrations Laravel (`database/migrations/`) :

```bash
# Exécuter les migrations en production
php artisan migrate --force
```

---

### Cas 2 : Données initiales ou mises à jour via Seeders Laravel
Si vous devez insérer de nouveaux paramètres ou données fixes :

```bash
php artisan db:seed --force
```

---

### Cas 3 : Import d'un fichier SQL complet (Dump de base de données)

#### Option A : Via l'interface web phpMyAdmin d'OVH (Méthode visuelle la plus simple)
1. Dans votre espace **OVH** > **Hébergements** > **Bases de données**.
2. Cliquez sur les **3 petits points** à droite de votre base > **Accéder à phpMyAdmin**.
3. Connectez-vous avec vos identifiants MySQL OVH.
4. Dans le menu du haut, cliquez sur **Importer**.
5. Cliquez sur **Parcourir** et sélectionnez votre fichier `.sql` présent sur votre ordinateur.
6. Cliquez sur le bouton **Exécuter** tout en bas.

---

#### Option B : Via la ligne de commande SSH (Rapide et direct)

##### 1. Exporter votre base de données locale (sur votre ordinateur Mac) :
Dans votre terminal local (sur votre Mac) :
```bash
# Exporter la base locale vers un fichier .sql
mysqldump -u root -p kimboo > kimboo_maj.sql
```

##### 2. Envoyer le fichier `.sql` sur votre serveur OVH :
Toujours depuis votre Mac :
```bash
scp kimboo_maj.sql VOTRE_UTILISATEUR@ssh.clusterXXX.hosting.ovh.net:~/www/
```

##### 3. Importer le fichier `.sql` dans la base de données OVH :
Connectez-vous en SSH sur OVH, puis exécutez :
```bash
mysql -h HOTE_BD_OVH -u UTILISATEUR_BD_OVH -p NOM_DE_LA_BD < kimboo_maj.sql
```
*(Remplacez `HOTE_BD_OVH`, `UTILISATEUR_BD_OVH` et `NOM_DE_LA_BD` par les valeurs trouvées dans votre fichier `.env` sur le serveur).*

##### 4. Supprimer le fichier SQL du serveur par sécurité :
```bash
rm kimboo_maj.sql
```

---

## 5. Sauvegarde de sécurité de la base de données OVH avant modification

Avant d'effectuer une grosse mise à jour sur la base de données en production, il est fortement recommandé de créer une sauvegarde :

```bash
# Créer une sauvegarde horodatée sur le serveur OVH
mysqldump -h HOTE_BD_OVH -u UTILISATEUR_BD_OVH -p NOM_DE_LA_BD > backup_$(date +%Y%m%d_%H%M%S).sql
```

---

## 6. Récapitulatif : Routine de mise à jour rapide

À chaque fois que vous faites des modifications :

```bash
# 1. Se connecter en SSH
ssh VOTRE_UTILISATEUR@ssh.clusterXXX.hosting.ovh.net

# 2. Aller dans le dossier du projet
cd www

# 3. Récupérer le code et appliquer les migrations
git pull origin main
php artisan migrate --force
php artisan view:clear
php artisan optimize
```
