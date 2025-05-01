# 🎵 Music Blog — Projet Symfony (TP scolaire)

![Aperçu du site](public/images/preview_site.png)

Ce projet a été réalisé dans le cadre d’un **travail pratique scolaire** visant à tester et mettre en application les connaissances acquises sur le framework **Symfony** après un cours complet.

---

## 🚀 Technologies utilisées

- **PHP** : 8.3.14
- **Symfony** : 7.2.5
- **Base de données** : MySQL 8.0.40
- **Serveur local** : MAMP
- **PDF** : DomPDF
- **Front-end** : Tailwind CSS, FontAwesome
- **API musicale** : Deezer (appel depuis un service Symfony)
- **Authentification** : JWT (LexikJWTAuthenticationBundle)

---

## ⚙️ Installation & Setup

```bash
# Cloner le dépôt
git https://github.com/jeremy-prt/music_blog_symfony.git
cd music-blog

# Installer les dépendances
composer install

# Copier le fichier .env et configurer l'accès BDD
cp .env .env.local
# Modifier la ligne DATABASE_URL en fonction de votre config locale MAMP par exemple :
# DATABASE_URL="mysql://root:root@127.0.0.1:8889/music_blog?serverVersion=8.0.40"

# Créer la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Charger les fixtures
php bin/console doctrine:fixtures:load
```

> ℹ️ Les fixtures insèrent **des articles de test** ainsi que **deux comptes utilisateurs** :
>
> - **Administrateur** : `admin@blog.test` / `adminpass`
> - **Utilisateur classique** : `user@blog.test` / `userpass`

Chaque utilisateur inscrit via le formulaire sera **par défaut un compte USER**, sans droits admin.

---

## 📄 Génération de PDF (fonction asynchrone)

L’export des articles au format PDF se fait via Messenger en tâche de fond.  
Pour que cela fonctionne :

```bash
php bin/console messenger:consume async -vv
```

> Un bouton "Générer le PDF" est disponible sur chaque page d’article.  
> Une fois généré, un lien "Voir le PDF" apparaîtra automatiquement.

---

## 🔌 API REST

### Accès aux routes publiques (GET)

- Liste des articles :  
  `GET /api/articles`
- Détail d’un article :  
  `GET /api/articles/{id}`

### Authentification (JWT)

Avant d’utiliser les routes protégées, connectez-vous en POST sur :

```http
POST /login
Content-Type: application/json

{
  "email": "admin@blog.test",
  "password": "adminpass"
}
```

> Le token JWT vous sera retourné. Il est requis pour les routes POST / PUT.

---

### Ajouter un article

```http
POST /api/articles
Authorization: Bearer VOTRE_JWT_ICI
Content-Type: application/json

{
  "titre": "Nouvel article API",
  "slug": "nouvel-article-api",
  "contenu": "Ceci est un article posté via l'API.",
  "artiste": "Zion",
  "datePublication": "2025-05-01T10:30:00"
}
```

---

### Modifier un article

```http
PUT /api/articles/{id}
Authorization: Bearer VOTRE_JWT_ICI
Content-Type: application/json
```

---

## 📁 Structure utile

- `src/DataFixtures/UserFixtures.php` : définition des utilisateurs de test.
- `src/DataFixtures/ArticleFixtures.php` : articles d’exemple insérés.
- `src/Message/ExportPdf.php` : message utilisé pour la génération PDF.
- `src/MessageHandler/ExportPdfMessageHandler.php` : handler associé.

---

## 🧪 Tests

Pas de tests automatisés dans ce projet, mais les fonctionnalités principales ont été validées manuellement (inscription, authentification, API CRUD, export PDF, Deezer API, etc.).

---

## 📝 Remarques

Ce projet n’a pas été designé de façon poussée par manque de temps. L’objectif principal était la **validation fonctionnelle**.  
Une **popup d'avertissement** s’affiche sur certaines pages pour en informer.

---

## 📷 Capture d’écran

![Capture](public/images/capture_article_detail.png)
