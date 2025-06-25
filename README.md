# Projet Auto-École Castellane

## Description
Site web complet pour la gestion d'une auto-école développé avec HTML, CSS, JavaScript, PHP et MySQL.

## Fonctionnalités

### Pour les candidats :
- Inscription et connexion
- Consultation des leçons programmées
- Suivi des examens (code et conduite)
- Gestion du profil personnel

### Pour les moniteurs :
- Consultation du planning des leçons
- Gestion des élèves assignés
- Suivi des véhicules utilisés

### Pour les administrateurs :
- Gestion complète des utilisateurs
- Gestion des véhicules
- Planification des leçons
- Gestion des examens
- Statistiques et rapports

## Technologies utilisées
- **Frontend** : HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend** : PHP 7+
- **Base de données** : MySQL
- **Outils** : Git, VS Code

## Structure du projet

```
auto-ecole-project/
├── index.html              # Page d'accueil
├── connexion.html           # Page de connexion/inscription
├── tarifs.html             # Page des tarifs
├── photos.html             # Page des véhicules
├── dashboard-candidat.html  # Tableau de bord candidat
├── styles.css              # Styles CSS principaux
├── auth.js                 # Gestion de l'authentification
├── dashboard-candidat.js   # JavaScript du tableau de bord candidat
├── auto_ecole.sql          # Script de création de la base de données
├── config/
│   └── database.php        # Configuration de la base de données
├── classes/
│   ├── User.php            # Classe de gestion des utilisateurs
│   ├── Lecon.php           # Classe de gestion des leçons
│   └── Vehicule.php        # Classe de gestion des véhicules
└── api/
    ├── auth.php            # API d'authentification
    ├── lecons.php          # API de gestion des leçons
    └── vehicules.php       # API de gestion des véhicules
```

## Installation

### Prérequis
- Serveur web (Apache/Nginx)
- PHP 7.0 ou supérieur
- MySQL 5.7 ou supérieur
- Extension PHP PDO

### Étapes d'installation

1. **Cloner ou télécharger le projet**
   ```bash
   git clone [URL_DU_DEPOT]
   cd auto-ecole-project
   ```

2. **Configurer la base de données**
   - Créer une base de données MySQL nommée `auto_ecole`
   - Importer le fichier `auto_ecole.sql`
   ```sql
   mysql -u root -p auto_ecole < auto_ecole.sql
   ```

3. **Configurer la connexion à la base de données**
   - Modifier le fichier `config/database.php`
   - Ajuster les paramètres de connexion (host, username, password)

4. **Déployer sur le serveur web**
   - Copier tous les fichiers dans le répertoire web
   - S'assurer que PHP a les permissions d'écriture nécessaires

## Configuration

### Base de données
Modifier les paramètres dans `config/database.php` :
```php
private $host = 'localhost';
private $db_name = 'auto_ecole';
private $username = 'votre_utilisateur';
private $password = 'votre_mot_de_passe';
```

### Comptes par défaut
Après l'installation, vous pouvez créer des comptes via l'interface d'inscription ou directement en base de données.

## Utilisation

### Accès au site
1. Ouvrir `index.html` dans un navigateur
2. Naviguer vers "Connexion" pour s'inscrire ou se connecter
3. Choisir le type de profil (candidat, moniteur, administrateur)

### Types d'utilisateurs

#### Candidat
- Peut consulter ses leçons programmées
- Peut voir ses dates d'examens
- Peut modifier certaines informations de profil

#### Moniteur
- Peut consulter son planning
- Peut voir la liste de ses élèves
- Peut consulter les véhicules disponibles

#### Administrateur
- Accès complet à toutes les fonctionnalités
- Gestion des utilisateurs, véhicules, leçons
- Accès aux statistiques

## API

Le projet inclut une API REST pour :

### Authentification (`/api/auth.php`)
- `POST` : Connexion et inscription

### Leçons (`/api/lecons.php`)
- `GET` : Récupérer les leçons
- `POST` : Créer une leçon
- `PUT` : Modifier une leçon
- `DELETE` : Supprimer une leçon

### Véhicules (`/api/vehicules.php`)
- `GET` : Récupérer les véhicules
- `POST` : Créer un véhicule
- `PUT` : Modifier un véhicule
- `DELETE` : Supprimer un véhicule

## Sécurité

- Mots de passe hashés avec `password_hash()`
- Protection contre les injections SQL avec PDO
- Validation et nettoyage des données d'entrée
- Gestion des sessions côté client avec localStorage

## Développement

### Ajout de nouvelles fonctionnalités
1. Créer les nouvelles classes PHP dans `/classes/`
2. Développer les API correspondantes dans `/api/`
3. Créer les interfaces utilisateur en HTML/CSS/JS
4. Mettre à jour la base de données si nécessaire

### Tests
- Tester toutes les fonctionnalités dans différents navigateurs
- Vérifier la responsivité sur mobile et tablette
- Tester les API avec des outils comme Postman

## Maintenance

### Sauvegarde
- Sauvegarder régulièrement la base de données
- Versionner le code avec Git

### Mises à jour
- Maintenir PHP et MySQL à jour
- Surveiller les vulnérabilités de sécurité
- Mettre à jour Bootstrap et les autres dépendances

## Support

Pour toute question ou problème :
1. Vérifier la documentation
2. Consulter les logs d'erreur du serveur
3. Vérifier la configuration de la base de données

## Licence

Ce projet est développé dans le cadre d'un projet éducatif BTS SIO.

## Auteur

Développé par Charles-Alexandre MOUNANOU dans le cadre du BTS Services informatiques aux organisations - Session 2025.

