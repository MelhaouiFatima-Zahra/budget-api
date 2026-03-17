# 💰 BudgetAPI

API REST complète pour la gestion de budget personnel.
Construite avec Symfony 7 et API Platform 3.

## 🚀 Technologies

- PHP 8.3
- Symfony 7
- API Platform 3
- MySQL 8
- JWT Authentication
- PHPUnit

## 📦 Installation
```bash
# Cloner le projet
git clone https://github.com/TON_USERNAME/budget-api.git
cd budget-api

# Installer les dépendances
composer install

# Configurer l'environnement
cp .env .env.local
# Modifier DATABASE_URL dans .env.local

# Générer les clés JWT
php bin/console lexik:jwt:generate-keypair

# Créer la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Lancer le serveur
symfony server:start
```

## 🔗 Routes principales

| Méthode | Route | Description |
|---------|-------|-------------|
| POST | /api/login | Connexion |
| POST | /api/register | Inscription |
| GET | /api/comptes | Mes comptes |
| GET | /api/transactions | Mes transactions |
| GET | /api/budgets | Mes budgets |
| GET | /api/objectifs | Mes objectifs |

## 🧪 Tests
```bash
php bin/phpunit
```
