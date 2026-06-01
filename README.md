# 🏦 Chama Management Platform

A full-stack **Chama Management System** built with Laravel and PostgreSQL.  
It manages members, contributions, loans, repayments, penalties, and system rules.

---

## 🚀 Features

- 👥 Member management
- 💰 Contributions tracking
- 🏦 Loan issuance & lifecycle tracking
- 📅 Loan & contribution schedules
- 📊 Repayments with penalties
- 🔐 Role-based access control
- ⚙️ System configuration via settings
- 📄 Audit-ready database structure

---

## 🧱 Tech Stack

- Backend: Laravel 13
- Database: PostgreSQL (production & local)
- Frontend: Blade + Tailwind CSS
- Authentication: Laravel Auth / Spatie Permissions
- ORM: Eloquent
- Seeder system for initial setup

---

## ⚙️ Requirements

- PHP 8.2+
- Composer
- PostgreSQL 18
- Node.js (optional for frontend assets)
- Git

---

## 📦 Installation

### 1. Clone the repository

```bash
git clone https://github.com/crispusomollo/chama.git
cd chama
```

### 2. Install dependencies
```bash
composer install
```

### 3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database (Local PostgreSQL)

```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=chama_local
DB_USERNAME=chama_user
DB_PASSWORD=secret123
```

### 5. Create database & user (if not already)

```bash
CREATE DATABASE chama_local;
CREATE USER chama_user WITH PASSWORD 'secret123';
GRANT ALL PRIVILEGES ON DATABASE chama_local TO chama_user;
```

### 6. Run migrations

```bash
php artisan migrate
```

### 7. Seed the database

```bash
php artisan db:seed
```
or full reset
```bash
php artisan migrate:fresh --seed
```

### 8. Start development server

```bash
php artisan serve
```

Visit:
http://127.0.0.1:8000