# Database Schema

This document outlines the database structure and relationships for the Eheca application.

## Table of Contents
- [Overview](#overview)
- [Tables](#tables)
  - [users](#users)
  - [roles](#roles)
  - [permissions](#permissions)
  - [user_roles](#user_roles)
  - [role_permissions](#role_permissions)
  - [password_resets](#password_resets)
  - [sessions](#sessions)
  - [failed_jobs](#failed_jobs)
- [Relationships](#relationships)
- [Indexes](#indexes)
- [Migrations](#migrations)
- [Seeding](#seeding)

## Overview

The database follows a relational model with the following key characteristics:

- **Engine**: InnoDB (for transaction support and foreign key constraints)
- **Character Set**: utf8mb4 (full Unicode support)
- **Collation**: utf8mb4_unicode_ci (case-insensitive, accent-insensitive)
- **Naming Conventions**:
  - Table names: plural, snake_case (e.g., `users`, `user_roles`)
  - Primary keys: `id` (auto-incrementing bigint)
  - Foreign keys: `table_name_id` (e.g., `user_id`)
  - Timestamps: `created_at`, `updated_at`, `deleted_at` (soft deletes)

## Tables

### users

Stores user account information.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string(255) | User's full name |
| email | string(255) | Unique email address |
| email_verified_at | timestamp | When email was verified |
| password | string(255) | Hashed password |
| remember_token | string(100) | For "remember me" functionality |
| is_active | boolean | Whether the account is active |
| last_login_at | timestamp | Last login timestamp |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Record last update time |
| deleted_at | timestamp | Soft delete timestamp |

### roles

Defines user roles in the system.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string(50) | Role name (e.g., 'admin', 'user') |
| description | string(255) | Role description |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Record last update time |

### permissions

Defines individual permissions that can be assigned to roles.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string(100) | Permission name (e.g., 'users.create') |
| description | string(255) | Permission description |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Record last update time |

### user_roles

Many-to-many relationship between users and roles.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users.id |
| role_id | bigint | Foreign key to roles.id |
| created_at | timestamp | Record creation time |

### role_permissions

Many-to-many relationship between roles and permissions.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| role_id | bigint | Foreign key to roles.id |
| permission_id | bigint | Foreign key to permissions.id |
| created_at | timestamp | Record creation time |

### password_resets

Stores password reset tokens.

| Column | Type | Description |
|--------|------|-------------|
| email | string(255) | User's email |
| token | string(255) | Reset token |
| created_at | timestamp | Token creation time |

### sessions

Active user sessions.

| Column | Type | Description |
|--------|------|-------------|
| id | string(255) | Session ID |
| user_id | bigint | Foreign key to users.id |
| ip_address | string(45) | User's IP address |
| user_agent | text | User's browser info |
| payload | text | Session data |
| last_activity | int | Timestamp of last activity |

### failed_jobs

Stores information about failed queued jobs.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| connection | text | Queue connection name |
| queue | text | Queue name |
| payload | longtext | Job payload |
| exception | longtext | Exception details |
| failed_at | timestamp | Failure timestamp |

## Relationships

1. **users** 1:n **user_roles**
   - `users.id` → `user_roles.user_id`

2. **roles** 1:n **user_roles**
   - `roles.id` → `user_roles.role_id`

3. **roles** 1:n **role_permissions**
   - `roles.id` → `role_permissions.role_id`

4. **permissions** 1:n **role_permissions**
   - `permissions.id` → `role_permissions.permission_id`

5. **users** 1:n **sessions**
   - `users.id` → `sessions.user_id`

## Indexes

### Primary Keys
- All tables have an auto-incrementing `id` primary key

### Foreign Keys
- `user_roles.user_id` → `users.id`
- `user_roles.role_id` → `roles.id`
- `role_permissions.role_id` → `roles.id`
- `role_permissions.permission_id` → `permissions.id`
- `sessions.user_id` → `users.id`

### Unique Indexes
- `users.email` (unique)
- `roles.name` (unique)
- `permissions.name` (unique)
- `user_roles` (composite unique on user_id, role_id)
- `role_permissions` (composite unique on role_id, permission_id)

### Performance Indexes
- `users.email_verified_at`
- `users.is_active`
- `users.deleted_at`
- `sessions.last_activity`
- `failed_jobs.failed_at`

## Migrations

Database migrations are managed using Laravel's migration system. To run migrations:

```bash
# Run all pending migrations
php artisan migrate

# Rollback the last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Rollback and re-run all migrations
php artisan migrate:refresh

# Create a new migration
php artisan make:migration create_table_name_table
```

## Seeding

Database seeders are available to populate the database with test data:

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UsersTableSeeder

# Refresh database and seed
php artisan migrate:refresh --seed
```

### Available Seeders

1. **RolesAndPermissionsSeeder**
   - Creates default roles (admin, user)
   - Sets up basic permissions
   - Assigns permissions to roles

2. **UsersTableSeeder**
   - Creates admin user
   - Optionally creates test users

3. **DummyDataSeeder**
   - Generates test data for development
   - Includes users, posts, comments, etc.

## Maintenance

### Backups

```bash
# Export database
mysqldump -u [username] -p [database] > backup_$(date +%Y%m%d).sql

# Import database
mysql -u [username] -p [database] < backup_file.sql
```

### Optimizations

```bash
# Optimize all tables
php artisan db:optimize

# Clear query cache
php artisan cache:clear

# Clear compiled views
php artisan view:clear
```

## Security Considerations

1. **Sensitive Data**
   - Passwords are hashed using bcrypt
   - API tokens are hashed in the database
   - Sensitive configuration is in `.env` (not version controlled)

2. **SQL Injection**
   - All queries use Laravel's query builder or Eloquent ORM
   - Input is validated before database operations

3. **Mass Assignment**
   - Protected `$guarded` or `$fillable` properties on models
   - Form request validation for input data
