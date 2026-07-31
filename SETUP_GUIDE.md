# Setup Guide - Prison Management System

## Step-by-Step Instructions to Run the Website

### Step 1: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Click **Start** button next to **Apache**
3. Click **Start** button next to **MySQL**
4. Wait until both show **Running** status (green)

### Step 2: Create the Database

**Option A: Using phpMyAdmin (Recommended)**

1. Open your web browser
2. Go to: `http://localhost/phpmyadmin`
3. Click on **"New"** in the left sidebar (or click **"Databases"** tab)
4. Enter database name: `prison_management`
5. Select **Collation**: `utf8mb4_general_ci`
6. Click **"Create"** button
7. Click on the `prison_management` database in the left sidebar
8. Click on **"Import"** tab at the top
9. Click **"Choose File"** button
10. Navigate to: the `database\schema.sql` file in your project folder
11. Click **"Go"** button at the bottom
12. Wait for success message: "Import has been successfully finished"

**Option B: Using SQL Tab**

1. Go to: `http://localhost/phpmyadmin`
2. Click on **"SQL"** tab
3. Open the file: `database/schema.sql` in a text editor
4. Copy ALL the contents
5. Paste into the SQL text area in phpMyAdmin
6. Click **"Go"** button
7. Wait for success message

### Step 3: Verify Database Setup

1. In phpMyAdmin, click on `prison_management` database
2. You should see these tables:
   - users
   - cells
   - staff
   - inmates
   - visitors
   - visits
   - activities
   - inmate_activities
   - medical_records
   - incidents

### Step 4: Access the Website

1. Open your web browser (Chrome, Firefox, Edge, etc.)
2. Go to: `http://localhost/prison-management-system/login.php`
   - OR: `http://localhost/prison-management-system/` (will redirect to login)
3. You should see the login page

### Step 5: Login

**Default Admin Credentials:**
- **Username:** `admin`
- **Password:** `admin123`

After login, you'll be redirected to the dashboard!

---

## Troubleshooting

### Problem: Apache won't start
**Solution:**
- Check if port 80 is already in use
- In XAMPP Control Panel, click **Config** → **httpd.conf**
- Change `Listen 80` to `Listen 8080` (or another port)
- Then access: `http://localhost:8080/prison-management-system/login.php`

### Problem: MySQL won't start
**Solution:**
- Check if port 3306 is already in use
- Close any other MySQL services
- Restart XAMPP Control Panel as Administrator

### Problem: Database connection error
**Solution:**
- Make sure MySQL is running in XAMPP
- Verify database name is `prison_management`
- Check `config/database.php` - default settings should work:
  - Host: `localhost`
  - User: `root`
  - Password: (empty)
  - Database: `prison_management`

### Problem: Page shows blank/white screen
**Solution:**
- Check Apache error logs: XAMPP → Apache → Logs
- Make sure PHP is enabled in XAMPP
- Check browser console (F12) for JavaScript errors

### Problem: CSS/JavaScript not loading
**Solution:**
- Make sure all files are in correct folders
- Check browser console (F12) for 404 errors
- Verify file paths in browser Network tab

---

## Quick Test Checklist

- [ ] Apache is running (green in XAMPP)
- [ ] MySQL is running (green in XAMPP)
- [ ] Database `prison_management` exists
- [ ] All tables are created
- [ ] Can access `http://localhost/prison-management-system/login.php`
- [ ] Login page displays correctly
- [ ] Can login with admin/admin123
- [ ] Dashboard loads after login

---

## Project Structure

```
prison-management-system/
├── api/              # API endpoints
├── assets/           # CSS and JavaScript
├── config/           # Configuration files
├── database/         # SQL schema file
├── includes/         # PHP includes
├── login.php         # Login page
├── dashboard.php     # Main dashboard
└── ...               # Other pages
```

---

## Need Help?

If you encounter any errors:
1. Check XAMPP Control Panel for error messages
2. Check browser console (Press F12)
3. Check Apache error logs
4. Verify all files are in the correct location

