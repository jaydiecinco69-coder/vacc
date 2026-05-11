# Animal Bite Center Management System

A comprehensive web-based management system for Animal Bite Centers in the Philippines to manage patient records, vaccination schedules, appointments, and treatment monitoring for animal bite cases such as rabies exposure.

## Features

### Core Functionality
- **Patient Management**: Complete patient records with medical history, bite details, and treatment progress tracking
- **Appointment Scheduling**: Smart scheduling system with calendar view, reminders, and queue management
- **Vaccination Monitoring**: Track vaccine schedules, dosage records, and treatment progress with timeline visualization
- **Report Generation**: Generate comprehensive reports with statistics, charts, and export capabilities
- **User Role Management**: Secure role-based access control (RBAC) for different user types
- **Notifications & Reminders**: Automated reminders for appointments, vaccinations, and follow-up schedules

### User Roles
- **Administrator**: Full system access, user management, reports, system settings
- **Healthcare Personnel/Staff**: Register patients, schedule appointments, record vaccinations, generate reports
- **Receptionist**: Register patients, schedule appointments, confirm schedules, view patient list
- **Patient**: Limited online access to view appointments, check vaccination schedules, receive reminders

### Technical Features
- **MVC Architecture**: Clean separation of concerns with Models, Views, and Controllers
- **Responsive Design**: Mobile-friendly interface that works on desktop, tablet, and mobile
- **Modern UI**: Clean medical/healthcare design with Tailwind CSS
- **Database Integration**: MySQL database with comprehensive schema for all data needs
- **Security**: Password hashing, session management, CSRF protection, input sanitization, change password support, secure session settings
- **API Integration**: External health tips and advisory content retrieved from public health-related APIs

## Requirements

### Server Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- Apache or Nginx web server
- PHP extensions: PDO, PDO_MySQL, mbstring, json, session

### Optional Requirements
- PDF generation library (TCPDF) for report exports
- Email configuration for notifications
- QR code library for patient ID generation

## Installation

### 1. Database Setup
1. Create a MySQL database named `bite_vaccination`
2. Import the database schema from `database/create_tables.sql`
3. Create a MySQL user with appropriate privileges

### 2. Configuration
1. Copy `config/config.php.example` to `config/config.php` (if it exists)
2. Update database connection details:
   ```php
   private $host = 'localhost';
   private $db_name = 'bite_vaccination';
   private $username = 'your_db_username';
   private $password = 'your_db_password';
   ```

### 3. Web Server Setup
1. Place all files in your web server's document root
2. Ensure the `BiteVaccination` directory is web-accessible
3. Set appropriate file permissions (755 for directories, 644 for files)
4. Configure your web server to point to the project directory

### 4. URL Configuration
Update the `BASE_URL` constant in `config/config.php`:
```php
define('BASE_URL', 'http://localhost/BiteVaccination/');
```

## Usage

### Accessing the System
1. Open your web browser and navigate to the system URL
2. You'll see the landing page with options to login or register
3. Default admin credentials:
   - Username: `admin`
   - Password: `admin123`

### First Steps
1. **Login as Administrator** to configure the system
2. **Create Staff Accounts** for healthcare personnel
3. **Register Patients** in the system
4. **Schedule Appointments** for patient visits
5. **Record Vaccinations** as they are administered
6. **Generate Reports** to track system performance

## Directory Structure

```
BiteVaccination/
├── config/                 # Configuration files
│   ├── database.php       # Database connection
│   └── config.php         # Application configuration
├── core/                   # Core framework files
│   ├── Controller.php      # Base controller class
│   └── Model.php          # Base model class
├── controllers/            # Application controllers
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── PatientController.php
│   ├── AppointmentController.php
│   ├── VaccinationController.php
│   └── ReportController.php
├── models/                 # Data models
│   ├── User.php
│   ├── Patient.php
│   ├── Appointment.php
│   └── Vaccination.php
├── views/                  # View templates
│   ├── auth/
│   ├── dashboard/
│   ├── patients/
│   ├── appointments/
│   ├── vaccinations/
│   ├── reports/
│   └── home/
├── database/               # Database files
│   └── create_tables.sql
└── index.php              # Entry point
```

## Database Schema

The system includes the following main tables:
- `users` - User accounts and authentication
- `patients` - Patient information and demographics
- `animal_bites` - Animal bite incident details
- `appointments` - Appointment scheduling and management
- `vaccinations` - Vaccination records and tracking
- `treatment_progress` - Overall treatment monitoring
- `notifications` - System notifications and reminders
- `reports` - Generated reports storage
- `activity_logs` - User activity audit trail
- `vaccine_inventory` - Vaccine stock management

## Security Features

- **Password Hashing**: All passwords are securely hashed using PHP's password_hash()
- **Session Management**: Secure session handling with proper configuration and session regeneration on login
- **Input Validation**: All user inputs are sanitized and validated
- **SQL Injection Prevention**: Prepared statements used throughout
- **CSRF Protection**: Token-based CSRF protection for forms
- **Change Password Support**: Logged-in users can update their password securely
- **Role-Based Access**: Users can only access features appropriate to their role

## Customization

### Adding New Features
1. Create new controllers in the `controllers/` directory
2. Create corresponding models in the `models/` directory
3. Create view templates in the `views/` directory
4. Add routes in `index.php`

### Modifying UI
- Edit CSS classes in view files
- Update Tailwind CSS configuration if needed
- Modify color scheme in the design system

### Database Modifications
- Create migration scripts for schema changes
- Update model classes to reflect changes
- Test thoroughly in development environment

## Troubleshooting

### Common Issues

**Database Connection Errors**
- Check database credentials in `config/database.php`
- Ensure MySQL service is running
- Verify database exists and user has permissions

**Permission Errors**
- Check file permissions on the project directory
- Ensure web server has read/write access
- Verify PHP error logs for specific issues

**Blank Pages**
- Check PHP error reporting settings
- Verify all required files are present
- Ensure proper routing configuration

**Session Issues**
- Check session storage directory permissions
- Verify session configuration in `php.ini`
- Ensure cookies are enabled in browser

### Error Reporting
Enable error reporting in development by setting:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

Check web server error logs for detailed information.

## API Endpoints

The system provides RESTful API endpoints for:
- Patient search and management
- Appointment scheduling
- Vaccination records
- Report generation
- User authentication

## Support

For support and updates:
1. Check the documentation for detailed guides
2. Review error logs for specific issues
3. Test in development environment before production deployment
4. Keep regular backups of database and files

## License

This project is open-source and available under the MIT License.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request with detailed description

## Version History

- **v1.0.0** - Initial release with core functionality
- Complete patient management system
- Appointment scheduling with calendar view
- Vaccination tracking and monitoring
- Report generation with PDF export
- Role-based user management
- Responsive design implementation

---

**Note**: This system is designed specifically for Animal Bite Centers in the Philippines but can be adapted for other regions and use cases.
