# Rejestr Pracowników Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a Laravel 13 skeleton for employee records with normalized RBAC, Admin CRUD, and Cloudflare Tunnel compatibility.

**Architecture:** MVC with a normalized Role-Based Access Control system. Access is managed via middleware and roles are stored in a many-to-many relationship with users.

**Tech Stack:** PHP 8.4, Laravel 13, Blade, MariaDB/PostgreSQL.

---

### Task 1: Infrastructure & Environment Setup

**Files:**
- Modify: `.env`
- Modify: `app/Providers/AppServiceProvider.php`
- Modify: `app/Http/Middleware/TrustProxies.php` (or equivalent in L13)

- [ ] **Step 1: Configure `.env` database settings**
```env
DB_DATABASE=wsb_2025_K06_1
DB_USERNAME=laravel_user
DB_PASSWORD=ZAQ!2wsxToWszystkoZmienia
```
- [ ] **Step 2: Force HTTPS in `AppServiceProvider`**
In `boot()` method:
```php
if (app()->environment('production')) {
    \URL::forceScheme('https');
}
```
- [ ] **Step 3: Configure `TrustProxies` for Cloudflare**
Set `$proxies = '*';` to trust Cloudflare Tunnel headers.

---

### Task 2: Database Schema (Normalized RBAC & Employees)

**Files:**
- Create: `database/migrations/xxxx_create_roles_table.php`
- Create: `database/migrations/xxxx_create_role_user_table.php`
- Create: `database/migrations/xxxx_add_employee_fields_to_users_table.php`
- Create: `database/migrations/xxxx_create_attachments_table.php`

- [ ] **Step 1: Create `roles` table** (id, name, timestamps)
- [ ] **Step 2: Create `role_user` pivot table** (user_id, role_id)
- [ ] **Step 3: Add employee fields to `users` table**
(`employee_id` unique, `department`, `phone`, `address`, `notes`, `photo_path`)
- [ ] **Step 4: Create `attachments` table** (id, user_id, file_path, file_name, timestamps)
- [ ] **Step 5: Run migrations**
`php artisan migrate`

---

### Task 3: Models & Relationships

**Files:**
- Modify: `app/Models/User.php`
- Create: `app/Models/Role.php`
- Create: `app/Models/Attachment.php`

- [ ] **Step 1: Implement `Role` model** (Fillable: name)
- [ ] **Step 2: Implement `Attachment` model** (BelongsTo: User)
- [ ] **Step 3: Update `User` model**
    - Add `roles()` relationship (BelongsToMany)
    - Add `attachments()` relationship (HasMany)
    - Add `hasRole($role)` helper method.

---

### Task 4: Seeding Test Data

**Files:**
- Create: `database/seeders/RoleSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 1: Create `RoleSeeder`**
Seed: 'administrator', 'kierownik', 'pracownik'.
- [ ] **Step 2: Update `DatabaseSeeder`**
Create 3 users, each assigned one of the roles, with hashed passwords.
- [ ] **Step 3: Run seeders**
`php artisan db:seed`

---

### Task 5: RBAC Middleware & Routing

**Files:**
- Create: `app/Http/Middleware/RoleMiddleware.php`
- Modify: `app/Http/Kernel.php` (or `bootstrap/app.php` in L13)
- Modify: `routes/web.php`

- [ ] **Step 1: Implement `RoleMiddleware`**
Check if `$request->user()->hasRole($role)`. Redirect with 403 if fail.
- [ ] **Step 2: Register middleware alias** as `'role'`.
- [ ] **Step 3: Define route groups**
    - `auth` $\rightarrow$ `/dashboard`
    - `auth, role:administrator` $\rightarrow$ `/admin/*`
    - `auth, role:administrator,kierownik` $\rightarrow$ `/manager/*`
- [ ] **Step 4: Implement custom login redirection logic** based on user roles.

---

### Task 6: Administrator User CRUD (Core Logic)

**Files:**
- Create: `app/Http/Requests/UserStoreRequest.php`
- Create: `app/Http/Requests/UserUpdateRequest.php`
- Create: `app/Http/Controllers/Admin/UserController.php`

- [ ] **Step 1: Implement `UserStoreRequest`** (employee_id unique, photo image max 2MB)
- [ ] **Step 2: Implement `UserUpdateRequest`** (employee_id unique except current user)
- [ ] **Step 3: Implement `UserController@index`** (List users with roles)
- [ ] **Step 4: Implement `UserController@store`** (Create user, hash password, attach role)
- [ ] **Step 5: Implement `UserController@update`** (Update user, sync roles, handle file updates)
- [ ] **Step 6: Implement `UserController@destroy`** (Delete user and associated files)

---

### Task 7: Blade Views & File Handling

**Files:**
- Create: `resources/views/admin/users/index.blade.php`
- Create: `resources/views/admin/users/create.blade.php`
- Create: `resources/views/admin/users/edit.blade.php`
- Create: `resources/views/layouts/app.blade.php` (update for role-based menu)

- [ ] **Step 1: Run `php artisan storage:link`**
- [ ] **Step 2: Create User List view** with table and Admin links.
- [ ] **Step 3: Create User Form view** (Create/Edit) with file inputs for photo and attachments.
- [ ] **Step 4: Implement logic to display photos/attachments** via `asset('storage/...')`.

---

### Task 8: Documentation & Finalization

**Files:**
- Create: `README.md`

- [ ] **Step 1: Generate `README.md`**
    - Functionality overview.
    - Debian deployment guide (Nginx, `www-data` permissions).
    - Cloudflare Tunnel setup instructions.
- [ ] **Step 2: Run `vendor/bin/pint --format agent`** to fix style.
- [ ] **Step 3: Final verification** of all roles and redirects.
