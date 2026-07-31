# Login Guide - Who Can Sign In?

## Available Users

### Default Admin User (Created Automatically)
- **Username:** `admin`
- **Password:** `admin123`
- **Role:** Administrator (Full Access)
- **Status:** Created when you import `database/schema.sql`

### User "carine" (Need to Create)
- **Username:** `carine`
- **Password:** `123`
- **Role:** Administrator (Full Access)
- **Status:** Must be created manually

---

## How to Check Available Users

1. **Open your browser**
2. **Go to:** `http://localhost/prison-management-system/check_users.php`
3. **This page will show:**
   - All users in the database
   - Their usernames, emails, and roles
   - Whether they are active or not

---

## How to Create User "carine"

### Method 1: Using PHP Script (Easiest)

1. **Open your browser**
2. **Go to:** `http://localhost/prison-management-system/add_user_carine.php`
3. **The script will:**
   - Check if user "carine" already exists
   - Create the user with password "123" if it doesn't exist
   - Show you the login credentials

### Method 2: Using phpMyAdmin

1. Go to: `http://localhost/phpmyadmin`
2. Select database: `prison_management`
3. Click on `users` table
4. Click "Insert" tab
5. Fill in:
   - username: `carine`
   - email: `carine@prison.com`
   - password: (use PHP to generate hash - see below)
   - role: `admin`
   - full_name: `Carine`
   - is_active: `1`
6. Click "Go"

**Note:** For password, you need to hash "123" using PHP's `password_hash()` function.

---

## How to Login

1. **Go to:** `http://localhost/prison-management-system/login.php`
2. **Enter credentials:**
   - Use `admin` / `admin123` OR
   - Use `carine` / `123` (if created)
3. **Click "Sign In"**

---

## Troubleshooting "Invalid Username or Password" Error

### Problem 1: Database Not Imported
**Solution:**
- Import `database/schema.sql` in phpMyAdmin
- This creates the default `admin` user

### Problem 2: User "carine" Doesn't Exist
**Solution:**
- Go to: `http://localhost/prison-management-system/add_user_carine.php`
- This will create the user automatically

### Problem 3: Wrong Password
**Solution:**
- Make sure you're using the exact password:
  - For `admin`: use `admin123` (not `Admin123` or `ADMIN123`)
  - For `carine`: use `123` (not `1234` or `Carine123`)

### Problem 4: Database Connection Error
**Solution:**
- Check if MySQL is running in XAMPP
- Verify database name is `prison_management`
- Check `config/database.php` settings

### Problem 5: User Account is Inactive
**Solution:**
- Go to phpMyAdmin
- Check `users` table
- Make sure `is_active` = `1` for your user

---

## Quick Steps to Get Started

1. ✅ **Start XAMPP** (Apache and MySQL)
2. ✅ **Import Database:**
   - Go to phpMyAdmin
   - Import `database/schema.sql`
3. ✅ **Create User "carine":**
   - Go to: `http://localhost/prison-management-system/add_user_carine.php`
4. ✅ **Check Users:**
   - Go to: `http://localhost/prison-management-system/check_users.php`
5. ✅ **Login:**
   - Go to: `http://localhost/prison-management-system/login.php`
   - Use: `admin` / `admin123` OR `carine` / `123`

---

## Need More Help?

- Check `check_users.php` to see all available users
- Verify database connection in `config/database.php`
- Check XAMPP error logs if issues persist


