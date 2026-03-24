# 🚀 Quick Start Guide - Organization Membership Management System

## ⚡ EASIEST WAY TO RUN (Windows)

### Option 1: Double-click `setup_and_run.bat`
This will automatically:
- Install all dependencies
- Set up the database
- Configure the application
- Start the server

**Just double-click the file and follow the prompts!**

### Option 2: Double-click `start_server.bat`
If already set up, just double-click this file to start the server quickly.

---

## 📋 Manual Setup Steps

If you prefer manual setup or encounter issues:

### Step 1: Install PHP (if not installed)

Download and install one of these:
- **XAMPP**: https://www.apachefriends.org/download.html (Recommended)
- **WAMP**: https://www.wampserver.com/en/
- **Standalone PHP**: https://windows.php.net/download/

**After installing XAMPP:**
1. Add PHP to your system PATH:
   - `C:\xampp\php` (or wherever you installed XAMPP)
2. Restart your terminal/command prompt

### Step 2: Install Node.js (if not installed)

Download from: https://nodejs.org/

### Step 3: Open Command Prompt in Project Folder

Navigate to: `d:\My_projects\Organization_membership_managment_system`

Or press `Shift + Right Click` in the folder → "Open PowerShell window here"

### Step 4: Run Setup Commands

```cmd
:: Install PHP dependencies
php composer.phar install

:: Create .env if needed
copy .env.example .env

:: Generate app key
php artisan key:generate

:: Install npm packages
npm install

:: Create SQLite database
type nul > database\database.sqlite

:: Run migrations
php artisan migrate --force

:: Seed plans
php artisan db:seed --class=PlanSeeder

:: Create storage link
php artisan storage:link

:: Start server
php artisan serve
```

### Step 5: Access Application

Open browser and go to: **http://localhost:8000**

---

## 👤 Create Test Users

After setup, create users by running these commands:

### Open Tinker (Interactive PHP Shell)
```cmd
php artisan tinker
```

### Create SuperAdmin
```php
App\Models\User::create(['name' => 'Super Admin', 'email' => 'admin@example.com', 'role' => 'SuperAdmin', 'password' => bcrypt('password123')]);
```

### Create Organization Admin
```php
App\Models\User::create(['name' => 'Org Admin', 'email' => 'orgadmin@example.com', 'role' => 'organAdmin', 'organization_name' => 'Test Organization', 'organization_type' => 'business', 'plan_id' => 1]);
```

### Create Member
```php
App\Models\User::create(['name' => 'John Member', 'email' => 'member@example.com', 'role' => 'member', 'organization_name' => 'Test Organization', 'plan_id' => 4]);
```

Type `exit` to leave tinker.

---

## 🔧 Troubleshooting

### "PHP not found" error
- Install XAMPP from https://www.apachefriends.org/
- Add `C:\xampp\php` to your Windows PATH environment variable
- Restart Command Prompt

### "composer.phar not found" error
- Download from https://getcomposer.org/download/
- Or use the included `composer.phar` in project root

### Database migration errors
```cmd
php artisan migrate:rollback
php artisan migrate:fresh --seed
```

### Port 8000 already in use
```cmd
php artisan serve --port=8080
```

### Clear all caches
```cmd
php artisan optimize:clear
```

---

## ✅ Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| SuperAdmin | admin@example.com | password123 |
| OrganAdmin | orgadmin@example.com | password123 |
| Member | member@example.com | password123 |

---

## 🎯 Features to Test

### As SuperAdmin:
- View all organizations
- Manage organization admins
- View all members and payments
- Create/edit/delete organizations

### As OrganAdmin:
- Add/manage members
- Create events and blogs
- Upgrade subscription plans
- View organization statistics

### As Member:
- View organization events
- Read blogs
- View personal profile

---

## 🌐 Google OAuth Setup (Optional)

To enable Google login:

1. Go to https://console.cloud.google.com/
2. Create a project
3. Enable Google+ API
4. Create OAuth 2.0 credentials
5. Add redirect URI: `http://localhost:8000/auth/google/callback`
6. Copy credentials to `.env`:

```env
GOOGLE_CLIENT_ID=your_client_id_here
GOOGLE_CLIENT_SECRET=your_client_secret_here
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
```

---

## 📞 Need Help?

If you encounter issues:
1. Check PHP version: `php -v` (should be 8.2+)
2. Verify extensions: `php -m` (should include sqlite3, mbstring, xml, curl)
3. Clear cache: `php artisan optimize:clear`
4. Check logs: `storage/logs/laravel.log`

---

## 🎉 You're Ready!

Visit **http://localhost:8000** and start using your membership management system!
