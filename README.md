# Pokémon League API

A RESTful API for managing a Pokémon League, built with Laravel and featuring token-based authentication and role-based access control.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Setup and Installation](#setup-and-installation)
- [Database Structure](#database-structure)
- [API Endpoints](#api-endpoints)
- [Authentication](#authentication)
- [Roles and Permissions](#roles-and-permissions)
- [Development Guidelines](#development-guidelines)
- [Testing](#testing)
- [License](#license)

## 🌟 Overview

The Pokémon League API allows management of trainers, Pokémon, and battles within a competitive Pokémon league environment. The system tracks trainer rankings based on battle outcomes and provides role-based access to various functionalities.

## ✨ Features

- **Authentication System**: Secure token-based authentication using Laravel Passport
- **Role-Based Access Control**: Different access levels for admin, trainer, and guest roles
- **Trainer Management**: Create, view, update, and delete trainer profiles
- **Pokémon Management**: Manage Pokémon with various attributes and assign them to trainers
- **Battle System**: Record battles between trainers and automatically update rankings
- **Ranking System**: View trainers ranked by their battle performance points

## 🛠️ Technology Stack

- **Language**: PHP 8.1+
- **Framework**: Laravel 10
- **Authentication**: Laravel Passport
- **Authorization**: Spatie/Laravel-Permission
- **Testing**: PHPUnit with TDD approach
- **Database**: MySQL/PostgreSQL

## 📥 Setup and Installation

### Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL or PostgreSQL
- Git

### Installation Steps

1. **Clone the repository**

```bash
git clone https://github.com/your-username/pokemon-league-api.git
cd pokemon-league-api
```

2. **Install dependencies**

```bash
composer install
```

3. **Set up environment variables**

```bash
cp .env.example .env
```

Edit the `.env` file to configure your database connection and other settings.

4. **Generate application key**

```bash
php artisan key:generate
```

5. **Run database migrations and seeders**

```bash
php artisan migrate
php artisan db:seed
```

6. **Install Passport**

```bash
php artisan passport:install
```

7. **Start the development server**

```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api`.

## 🗃️ Database Structure

The API uses the following main entities:

1. **Users**: Authentication entity with roles and permissions
2. **Trainers**: Domain entity for Pokémon trainers with trainer-specific data
3. **Pokemons**: Entities that can be assigned to trainers
4. **Battles**: Records of battles between trainers

## 🔌 API Endpoints

### Authentication Endpoints

| Method | Endpoint          | Description                           | Required Parameters                            |
|--------|-------------------|---------------------------------------|------------------------------------------------|
| POST   | `/register`       | Register new trainer                  | name, email, password, password_confirmation   |
| POST   | `/login`          | Login and get access token            | email, password                                |
| POST   | `/logout`         | Logout and invalidate token           | Bearer token in Authorization header           |

### Trainers Endpoints

| Method | Endpoint                | Description                               | Required Parameters      | Permissions      |
|--------|-------------------------|-------------------------------------------|--------------------------|------------------|
| GET    | `/trainers`             | List trainers (ordered by points)         | -                        | trainers.view    |
| POST   | `/trainers`             | Create new trainer                        | name, email              | trainers.create  |
| GET    | `/trainers/{id}`        | View trainer details with their pokemons  | -                        | trainers.view    |
| PUT    | `/trainers/{id}`        | Update trainer                            | name, email              | trainers.update  |
| DELETE | `/trainers/{id}`        | Delete trainer                            | -                        | trainers.delete  |
| GET    | `/trainers/ranking`     | View ranking (trainers by points)         | -                        | Public endpoint  |

### Pokemons Endpoints

| Method | Endpoint                                 | Description                           | Required Parameters            | Permissions      |
|--------|------------------------------------------|---------------------------------------|--------------------------------|------------------|
| GET    | `/pokemons`                              | List all pokemons                     | -                              | pokemons.view    |
| GET    | `/pokemons/available`                    | List pokemons without trainer         | -                              | pokemons.view    |
| POST   | `/pokemons`                              | Create new pokemon                    | name, type, level, stats       | pokemons.create  |
| GET    | `/pokemons/{id}`                         | View pokemon details                  | -                              | pokemons.view    |
| PUT    | `/pokemons/{id}`                         | Update pokemon                        | level, trainer_id              | pokemons.update  |
| POST   | `/pokemons/{id}/trainers/{trainer_id}`   | Assign pokemon to a trainer           | -                              | pokemons.update  |
| DELETE | `/pokemons/{id}/trainers/{trainer_id}`   | Release pokemon (remove trainer)      | -                              | pokemons.update  |

### Battles Endpoints

| Method | Endpoint            | Description                           | Required Parameters      | Permissions    |
|--------|---------------------|---------------------------------------|--------------------------|----------------|
| GET    | `/battles`          | List battles (ordered by date)        | -                        | battles.view   |
| POST   | `/battles`          | Create and perform new battle         | trainer1_id, trainer2_id, date | battles.create |
| GET    | `/battles/{id}`     | View battle details with trainers     | -                        | battles.view   |
| DELETE | `/battles/{id}`     | Delete battle and update points       | -                        | battles.delete |

## 🔐 Authentication

The API uses Laravel Passport for token-based authentication. To authenticate:

1. Register a new user or login with existing credentials
2. Use the returned token in the Authorization header for subsequent requests:
   ```
   Authorization: Bearer YOUR_TOKEN
   ```

## 👥 Roles and Permissions

### Roles

- **admin**: Full system access
- **trainer**: Limited access to their own resources
- **guest**: Access only to public information

### Permissions

- trainers.view, trainers.create, trainers.update, trainers.delete
- pokemons.view, pokemons.create, pokemons.update, pokemons.delete
- battles.view, battles.create, battles.delete

## 📝 Development Guidelines

### GitFlow Workflow

This project follows the GitFlow branching model:

- **main**: Production-ready code
- **develop**: Integration branch for development
- **feature/**: For new features (branched from develop)
- **release/**: For preparing releases (branched from develop)
- **hotfix/**: For urgent fixes to production (branched from main)

### Commit Conventions

- Use descriptive commit messages
- Prefix commits with type: `feat:`, `fix:`, `test:`, `docs:`, `refactor:`
- Reference task/issue numbers when applicable

## 🧪 Testing

The project follows Test-Driven Development (TDD) principles. Run tests with:

```bash
php artisan test
```

or

```bash
vendor/bin/phpunit
```

## 📄 License

[MIT License](LICENSE)

---

## 📞 Contact

For questions or support, please [open an issue](https://github.com/your-username/pokemon-league-api/issues) on the GitHub repository.