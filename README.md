# Rejestr Pracowników

## Overview
This is a Laravel 13 application designed for managing employee records with a normalized Role-Based Access Control (RBAC) system.

### Key Features
- **Normalized RBAC**: Three distinct roles: Administrator, Kierownik (Manager), and Pracownik (Employee).
- **Employee Management**: Full CRUD for users, including custom fields like Employee ID, Department, Phone, Address, and Notes.
- **File Handling**: Support for employee photos and document attachments stored in the public storage.
- **Role-Based Access**: Restricted access to management and administration panels.

## Deployment Guide (Debian Linux)

### Prerequisites
- PHP 8.3+
- PostgreSQL 16+
- Nginx or Apache
- Composer

### Installation
1. Clone the repository to `/var/www/rejestr-pracownikow`.
2. Install dependencies:
   ```bash
   composer update
   composer install
   npm install && npm run build
   ```
3. Set up the environment:
   ```bash
   docker run -d --name rejestr-pracownikow-db \
     -e POSTGRES_PASSWORD=haslo \
     -e POSTGRES_DB=rejestr_pracownikow \
     -p 5432:5432 postgres:16-alpine

   cp .env.example .env
   # Uzupelnij .env danymi dostepowymi do bazy:
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=rejestr_pracownikow
    DB_USERNAME=postgres
    DB_PASSWORD=haslo
   ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
6. Link storage:
   ```bash
   php artisan storage:link
   ```
7. Run test
   ```bash
   make test
   ```
8. Run app 
   ```bash
   make serve
   ```
   
### Server Permissions
Ensure the web server user (`www-data`) has ownership and write access to the necessary directories:
```bash
sudo chown -R www-data:www-data /var/www/rejestr-pracownikow/storage /var/www/rejestr-pracownikow/bootstrap/cache
sudo chmod -R 775 /var/www/rejestr-pracownikow/storage /var/www/rejestr-pracownikow/bootstrap/cache
```

## Access Levels
- **Administrator**: Access to everything, including User Management.
- **Kierownik**: Access to Dashboard and Manager Panel.
- **Pracownik**: Access to personal Dashboard.
