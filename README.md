# Prison Management System

A comprehensive web-based Prison Management System built with PHP, MySQL, HTML, CSS, and JavaScript. This system provides role-based access control with separate interfaces for administrators and regular users.

## Features

### Authentication & Authorization
- Secure login system with session management
- Role-based access control (Admin and User)
- Password hashing for security
- Session timeout protection

### Admin Features
- Full access to all modules
- User management (create, edit, delete users)
- Complete CRUD operations on all entities
- System reports and analytics

### User Features
- View-only access to most modules
- Limited editing capabilities
- Dashboard with statistics

### Core Modules

1. **Inmate Management**
   - Add, edit, delete inmates
   - Track inmate information (personal details, crimes, sentences)
   - Assign inmates to cells
   - Track inmate status (active, released, transferred)

2. **Staff Management**
   - Manage prison staff
   - Track positions, departments, and hire dates
   - Staff status management

3. **Cell Management**
   - Manage prison cells
   - Track cell capacity and occupancy
   - Cell types (single, shared, isolation)
   - Block organization

4. **Visitor Management**
   - Register visitors
   - Track visitor information and relationships

5. **Visit Scheduling**
   - Schedule visits between inmates and visitors
   - Track visit types (regular, legal, medical)
   - Visit status management

6. **Activity Programs**
   - Manage rehabilitation and educational programs
   - Track activity types (education, recreation, work, therapy, religious)
   - Assign instructors
   - Schedule activities

7. **Incident Reporting**
   - Report and track incidents
   - Categorize by type and severity
   - Link incidents to inmates/staff

8. **Medical Records**
   - Maintain medical records for inmates
   - Track conditions, diagnoses, treatments
   - Medication management

## Installation

### Prerequisites
- XAMPP (or any PHP/MySQL server)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web browser

### Setup Steps

1. **Clone/Download the project**
   ```bash
   Place the project in your XAMPP htdocs folder
   ```

2. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the database schema from `database/schema.sql`
   - Or run the SQL file directly

3. **Configuration**
   - Update database credentials in `config/database.php` if needed:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'prison_management');
     ```

4. **Access the Application**
   - Start Apache and MySQL in XAMPP
   - Navigate to: `http://localhost/BEST/login.php`

5. **Default Login Credentials**
   - Username: `admin`
   - Password: `admin123`

## Project Structure

```
BEST/
├── api/                 # API endpoints for AJAX requests
│   ├── inmates.php
│   ├── staff.php
│   ├── cells.php
│   ├── visitors.php
│   ├── visits.php
│   ├── activities.php
│   ├── incidents.php
│   ├── medical.php
│   └── users.php
├── assets/
│   ├── css/
│   │   └── style.css    # Main stylesheet
│   └── js/
│       ├── main.js      # Common JavaScript functions
│       ├── inmates.js
│       ├── staff.js
│       ├── cells.js
│       ├── visitors.js
│       ├── visits.js
│       ├── activities.js
│       ├── incidents.js
│       └── medical.js
├── config/
│   ├── config.php       # Application configuration
│   └── database.php     # Database connection
├── database/
│   └── schema.sql       # Database schema
├── includes/
│   ├── auth.php         # Authentication functions
│   ├── dashboard.php    # Dashboard functions
│   ├── header.php       # Header component
│   └── sidebar.php      # Sidebar navigation
├── dashboard.php        # Main dashboard
├── login.php            # Login page
├── logout.php           # Logout handler
├── inmates.php          # Inmate management page
├── staff.php            # Staff management page
├── cells.php            # Cell management page
├── visitors.php         # Visitor management page
├── visits.php           # Visit management page
├── activities.php       # Activity management page
├── incidents.php        # Incident management page
├── medical.php          # Medical records page
├── users.php            # User management (admin only)
└── README.md            # This file
```

## Security Features

- Password hashing using PHP's `password_hash()`
- SQL injection prevention using prepared statements
- XSS protection with `htmlspecialchars()` and `sanitizeInput()`
- Session-based authentication
- Role-based access control
- CSRF protection (can be enhanced)

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Architecture**: MVC-like structure with separation of concerns

## Design Patterns

- **Repository Pattern**: Database operations separated in API files
- **MVC Pattern**: Separation of Model (database), View (HTML), Controller (PHP)
- **Singleton Pattern**: Database connection management

## Best Practices Implemented

- Clean code structure
- Consistent naming conventions
- Error handling
- Input validation and sanitization
- Responsive design
- RESTful API design
- Modular code organization

## Future Enhancements

- Advanced reporting and analytics
- Export functionality (PDF, Excel)
- Email notifications
- Search and filtering improvements
- Image upload for inmates/staff
- Audit logging
- Backup and restore functionality

## License

This project is created for educational purposes as part of a final exam project.

## Author

Developed as a final exam project for Software Development course.
---

## Docker

This project can be run with Docker Compose. The repository includes a `docker/php/Dockerfile`, `docker/apache/vhost.conf`, and `docker-compose.yml`.

Quick start (Windows PowerShell):

1. Ensure Docker Desktop is running.
2. Edit `.env` to change ports or database credentials if needed.
3. Build and start services:

```powershell
docker compose up --build -d
```

Open in your browser:

- App: http://localhost:${APP_PORT:-8082}
- phpMyAdmin: http://localhost:${PMA_PORT:-8083}

Useful commands:

```powershell
# View logs
docker compose logs -f

# Stop services
docker compose down

# Rebuild and restart
docker compose up --build -d
```

**Note**: This is a demonstration project. For production use, additional security measures and optimizations should be implemented.

