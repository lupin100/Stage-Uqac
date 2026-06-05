# Site Web du Groupe de Recherches Informatique (G.R.I)

Ce dépôt rassemble l'intégralité du code source de l'application web officielle développée pour le **Groupe de Recherches Informatique (G.R.I)** de l'Université du Québec à Chicoutimi conçu dans le cadre du cours **8INF309 Stage-projet I** durant le semestre d'Hiver 2026.

L'application repose sur une architecture découplée, séparant le client et le serveur via une API REST sécurisée.

---

## Architecture & Stack Technique

### Frontend

* **Vue.js (v3)** : Framework JavaScript moderne configuré pour assurer une interface utilisateur hautement réactive, dynamique et fluide.
* **Vuetify** : Framework UI basé sur Material Design permettant de garantir une cohérence visuelle optimale et une compatibilité responsive complète.

### Backend & Base de données

* **Symfony & Doctrine ORM** : Framework PHP structuré gérant les interactions avec la base de données sous forme d'objets pour éliminer les failles de sécurité et accélérer le cycle de développement.
* **EasyAdmin Bundle** : Solution backend intégrée pour générer un panneau d'administration CRUD complet sans nécessiter de développements frontend redondants.
* **PostgreSQL** : Système de gestion de base de données relationnelle déporté sur l'infrastructure cloud Neon pour garantir la flexibilité et la portabilité des configurations.

### DevOps & CI/CD

* **Docker & Docker Compose** : Environnement de conteneurisation complet garantissant l'isolation des services (frontend, backend, base de données) et une uniformité stricte entre les machines des développeurs.
* **GitHub & Render** : Pipeline d'intégration et de déploiement continus (CI/CD). Chaque mise à jour sur la branche `main` déclenche automatiquement la reconstruction de l'image Docker et le redéploiement sur Render.

---

## Fonctionnalités Majeures

### 1. Logique d'Affichage Dynamique et Intelligente

* **Factorisation des vues** : Centralisation du rendu des profils individuels au sein d'un composant unique (`MembreView.vue`) pour éviter la duplication de code.
* **Rendu adaptatif basé sur les rôles** : Distinction automatique entre profils étudiants et membres du laboratoire. Les étudiants affichent leurs directeurs et sujets de mémoire , tandis que les membres exposent leur biographie, publications, projets supervisés et liste d'étudiants rattachés.

### 2. Moteur de Recherche et Algorithmes Avancés

* **Filtrage asynchrone côté serveur** : Le frontend construit dynamiquement des paramètres d'URL envoyés à l'API Symfony, optimisant les performances globales et la pagination des résultats (événements, projets, publications).
* **Gestion des boucles infinies** : Utilisation d'un système de groupes de sérialisation pour restreindre intelligemment les relations d'entités Doctrine appelées par l'API.

### 3. Back-Office Administrative Sécurisé

* **Architecture de sécurité centralisée** : Authentification et protection robustes basées sur le composant de sécurité natif de Symfony.
* **Pare-feu strict** : Les requêtes de consultation (`GET`) sont publiques, tandis que toutes les opérations d'écriture, modification et suppression (`POST`, `PUT`, `DELETE`) requièrent impérativement le privilège `ROLE_ADMIN`.

---

## Installation et Lancement en Local

### Prérequis

* Disposer de [Docker](https://docs.docker.com/get-docker/) et de [Docker Compose](https://docs.docker.com/compose/install/) installés sur votre environnement.

### Procédure d'installation

1. **Cloner le projet** :
```bash
git clone https://github.com/lupin100/Stage-Uqac.git
cd Stage-Uqac

```

2. **Configuration de l'environnement** :
* Dupliquez les fichiers `.env.example` en `.env` dans les répertoires frontend et backend.
* Renseignez vos variables locales (notamment l'URL de l'API `VITE_API_URL` et l'identifiant de connexion à la base de données `DATABASE_URL`).

3. **Instanciation des conteneurs Docker** :
```bash
docker-compose up --build -d

```

4. **Exécuter les migrations de base de données (Symfony)** :
```bash
docker-compose exec backend php bin/console doctrine:migrations:migrate

```


5. **Peuplement initial de la base de données (Données de test)** :
```bash
docker-compose exec backend php bin/console doctrine:fixtures:load

```
