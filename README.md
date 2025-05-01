# Music Blog — Projet Symfony (TP scolaire)

![Aperçu du site](public/images/preview_site.png)

Ce projet a été réalisé dans le cadre d’un **travail pratique scolaire** visant à tester et mettre en application les connaissances acquises sur le framework **Symfony** après un cours complet.

---

## Technologies utilisées

- **PHP** : 8.3.14
- **Symfony** : 7.2.5
- **Base de données** : MySQL 8.0.40
- **Serveur local** : MAMP
- **PDF** : DomPDF
- **Front-end** : Tailwind CSS, FontAwesome
- **API musicale** : Deezer (appel depuis un service Symfony)
- **Authentification** : JWT (LexikJWTAuthenticationBundle)

---

## Installation & Setup

```bash
# Cloner le dépôt
git clone https://github.com/jeremy-prt/music_blog_symfony.git
cd music_blog_symfony
```

```bash
# Installer les dépendances nécessaires au projet
composer install
```

```bash
# Configurer l'accès BDD dans le .env
# Modifier la ligne DATABASE_URL en fonction de votre config locale MAMP ou WAMP par exemple :
DATABASE_URL="mysql://root:root@127.0.0.1:8889/music_blog?serverVersion=8.0.40"
```

```bash
# Créer la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

```bash
# Charger les fixtures
php bin/console doctrine:fixtures:load
```

> Les fixtures insèrent **des articles de test** ainsi que **deux comptes utilisateurs** :
>
> - **Administrateur** : `admin@blog.test` / `adminpass`
> - **Utilisateur classique** : `user@blog.test` / `userpass`

Chaque utilisateur inscrit via le formulaire sera **par défaut un compte USER**, sans droits admin.

```bash
# Démarrer le serveur
php symfony server:start -d
```

---

## Génération de PDF (fonction asynchrone)

L’export des articles au format PDF se fait via Messenger en tâche de fond avec messenger.  
Pour que cela fonctionne :

```bash
php bin/console messenger:consume async -vv
```

> Un bouton "Générer le PDF" est disponible sur chaque page d’article.  
> Une fois généré, un bouton "Voir le PDF" apparaîtra automatiquement.

---

## Commentaires

Pour publier un commentaire, accédez à la page de détail d’un article. Vous devez être connecté pour pouvoir commenter.

---

## API REST

### Accès aux routes publiques (GET)

- Liste des articles :  
  `GET /api/articles`
- Détail d’un article :  
  `GET /api/articles/{id}`

### Authentification (JWT)

Avant d’utiliser les routes protégées, connectez-vous en POST sur :

- Login :  
  `POST /api/login`

```http
{
  "email": "admin@blog.test",
  "password": "adminpass"
}
```

> Le token JWT vous sera retourné. Il est requis pour les routes POST / PUT / DELETE.

### Accès aux routes protégées (POST / PUT / DELETE)

- Ajouter un article :  
  `POST /api/articles`

```http
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

- Ajouter un article :  
  `POST /api/articles/{id}`

```http
Authorization: Bearer VOTRE_JWT_ICI
Content-Type: application/json

{
  "titre": "Nouvel article API modifié",
  "slug": "nouvel-article-api-modifie",
  "contenu": "Ceci est un article posté via l'API.",
  "artiste": "Zion",
  "datePublication": "2025-05-01T10:30:00"
}
```

- Supprimer un article :  
  `DELETE /api/articles/{id}`

```http
Authorization: Bearer VOTRE_JWT_ICI
Content-Type: application/json
```

---

## Structure utile

- `src/DataFixtures/UserFixtures.php` : définition des utilisateurs de test.
- `src/DataFixtures/ArticleFixtures.php` : articles d’exemple insérés.

---

## Tests

Pas de tests automatisés dans ce projet, mais les fonctionnalités principales ont été validées manuellement (inscription, authentification, API CRUD, export PDF, Deezer API, etc.).
