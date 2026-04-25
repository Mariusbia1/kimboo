# Kimboo — Plateforme de mise en relation Élèves & Enseignants

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-Framework-red?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/PHP-Backend-blue?style=for-the-badge&logo=php">
  <img src="https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge&logo=mysql">
  <img src="https://img.shields.io/badge/Blade-Templating-black?style=for-the-badge">
  <img src="https://img.shields.io/badge/Status-Production--Ready-brightgreen?style=for-the-badge">
</p>

---

## Vision du projet

**Kimboo** est une application web complète conçue pour simplifier et moderniser la mise en relation entre élèves et enseignants.

L’objectif est de proposer une plateforme intuitive, performante et sécurisée permettant :

* une meilleure accessibilité à l’éducation
* une gestion fluide des cours
* une communication efficace entre utilisateurs

---

## Ce que démontre ce projet

Ce projet met en avant ma capacité à :

* Concevoir une **architecture backend robuste avec Laravel**
* Implémenter un **système complet multi-utilisateurs**
* Développer des **fonctionnalités avancées (messagerie, notifications, tracking)**
* Structurer une base de données avec migrations
* Créer une **interface utilisateur fonctionnelle et responsive**
* Gérer un projet réel de bout en bout (conception → développement → déploiement)

---

## Fonctionnalités clés

### Gestion des enseignants

* Création et gestion de profils
* Ajout de parcours académique
* Publication de cours
* Gestion des disponibilités

### Expérience élève

* Recherche de cours
* Ajout aux favoris
* Réservation de cours
* Gestion du profil

### Messagerie intelligente

* Conversations en temps réel
* Système de modération
* Détection de contenu inapproprié
* Alertes automatiques

### Notifications dynamiques

* Suivi des actions importantes
* Interaction en temps réel

### Système de tracking & statistiques

* Suivi des visites (Page Views)
* Analyse du comportement utilisateur
* Dashboard administrateur

### Espace administrateur

* Gestion globale de la plateforme
* Surveillance des messages
* Accès aux statistiques
* Contrôle des contenus

---

## Architecture du projet

```bash
app/
 ├── Http/Controllers   # Logique métier
 ├── Models             # Modèles Eloquent
 ├── Services           # Services métier (ex: modération)
 ├── Middleware         # Tracking & sécurité

database/
 ├── migrations         # Structure BDD

resources/
 ├── views              # Interfaces Blade
```

---

## Stack technique

| Technologie  | Rôle                  |
| ------------ | --------------------- |
| Laravel      | Framework backend     |
| TailwindCSS  | Framework frontend    |
| PHP          | Logique serveur       |
| MySQL        | Base de données       |
| Blade        | Templates             |
| JavaScript   | Interactions frontend |
| Git / GitHub | Versionning           |

---

## Installation rapide

```bash
git clone git@github.com:Mariusbia1/kimboo.git
cd kimboo
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

---

## Aperçu du projet

> À compléter par des captures d’écran :

* Page d’accueil
* Dashboard admin
* Interface élève
* Messagerie

---

## Axes d’amélioration

* Intégration de paiement (Stripe / Mobile Money)
* Système de notation des enseignants
* Version mobile (Flutter ou React Native)
* Optimisation des performances
* Renforcement de la sécurité

---

## À propos de moi

**Marius Biaou**
Développeur Web (Laravel / JavaScript)

Objectif : intégrer une entreprise pour contribuer à des projets concrets et monter en expertise.

🔗 GitHub : https://github.com/Mariusbia1

---

## Pourquoi ce projet est pertinent pour un recruteur ?

Kimboo n’est pas un simple projet académique.

C’est une **application complète**, qui démontre :

* une compréhension réelle des besoins utilisateurs
* une capacité à développer des fonctionnalités complexes
* une approche structurée du développement

Ce projet reflète directement mon niveau actuel et mon potentiel en entreprise.


## 📄 Licence

Projet open-source à but éducatif.
