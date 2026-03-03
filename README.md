# Admin-Only Laravel Admin Panel

Admin-only web application built with Laravel + MySQL.

## Features

- Only one role: **Admin**
- Public users can access the app (no admin access)
- Public user activity tracking (`application_users` table)
- Session-based authentication (`admins` table)
- Protected admin routes (Dashboard, Master Item, User Details, Settings)
- Bootstrap 5 layout with collapsible sidebar + icons

## Setup (XAMPP / MySQL)

1. Create a database named `admin_panel`.
2. Update `.env` with your MySQL credentials (DB\_*).
3. Run migrations and seed the default admin:

   - `php artisan migrate`
   - `php artisan db:seed`

4. Open:

   - Public app: `http://localhost/admin_panel/public/`
   - Admin login: `http://localhost/admin_panel/public/admin/login`

## Default Admin (change after first login)

- Username: `admin`
- Password: `Admin@12345`

## SQL Schema (optional)

- `database/schema.sql`
