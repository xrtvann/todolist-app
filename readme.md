<p align="center">
  <img src="https://img.icons8.com/fluency/96/000000/todo-list.png" alt="ToDo List Icon" width="96" height="96"/>
</p>

<h1 align="center">🚀 ToDo List App</h1>

<p align="center">
  <strong>A modern and user-friendly To-Do List web application built with PHP, MySQL, and Tailwind CSS</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-7.4+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/TailwindCSS-3.0+-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat-square&logo=javascript&logoColor=black" alt="JavaScript">
</p>

---

## ✨ Features

### 🔐 **Authentication & Security**

- **Secure User Registration & Login** - Complete authentication system with encrypted sessions
- **AES-256-CBC Encryption** - Enterprise-level session data encryption
- **User Data Isolation** - Each user can only access their own data
- **Remember Me Functionality** - Secure cookie-based persistent login
- **Session Validation** - Multi-layer session integrity checks

### 📋 **Task Management**

- **Full CRUD Operations** - Create, read, update, and delete tasks
- **Task Status Management** - Mark tasks as pending or completed
- **Custom Task IDs** - Auto-generated prefixed IDs (TSK-001, TSK-002, etc.)
- **Category Assignment** - Organize tasks with custom categories
- **Date Tracking** - Automatic creation timestamps with formatted display

### �️ **Category System**

- **Category Management** - Create, edit, and delete task categories
- **Category Icons** - Visual folder icons for better organization
- **Category-Task Relationships** - Link tasks to specific categories
- **Custom Category IDs** - Auto-generated prefixed IDs (CTRGY-001, etc.)

### �🔍 **Search & Filtering**

- **Live Search** - Real-time AJAX search across tasks and categories
- **Instant Results** - Search results update as you type
- **Responsive Search** - Optimized search experience on all devices
- **Filter Options** - Advanced filtering capabilities (ready for expansion)

### 📊 **Dashboard & Analytics**

- **Statistics Overview** - View task completion statistics
- **Progress Tracking** - Monitor your productivity over time
- **Quick Actions** - Fast access to common operations
- **Data Visualization** - Clean presentation of your task data

### 📱 **Mobile-First Responsive Design**

- **Fully Mobile Optimized** - Perfect experience on phones, tablets, and desktops
- **Touch-Friendly Interface** - Large buttons and touch targets for mobile
- **Horizontal Table Scrolling** - Smooth table navigation on small screens
- **Responsive Modals** - Mobile-optimized add/edit forms
- **Adaptive Layouts** - Content reflows beautifully across screen sizes
- **Mobile Sidebar** - Collapsible navigation for mobile devices

### 🎨 **Modern User Interface**

- **Tailwind CSS Framework** - Custom-built responsive design system
- **SweetAlert2 Integration** - Beautiful notifications and confirmations
- **FontAwesome Icons** - Comprehensive icon library for better UX
- **Hover Effects** - Smooth animations and interactive elements
- **Loading States** - Visual feedback for user actions
- **Empty States** - Helpful messages when no data is available

### ⚡ **Performance & Optimization**

- **AJAX-Powered** - Fast, seamless user interactions
- **Optimized Database Queries** - Efficient pagination and data retrieval
- **CSS Build Pipeline** - Optimized Tailwind CSS compilation
- **Lazy Loading** - Efficient resource loading
- **Caching Strategies** - Optimized session and data management

### 🛡️ **Advanced Security Features**

- **SQL Injection Protection** - Parameterized queries and input sanitization
- **XSS Prevention** - Proper output encoding and validation
- **CSRF Protection** - Secure form handling
- **Environment Variables** - Secure configuration management
- **User Authorization** - Role-based access control ready

---

## 🏗️ Project Structure

```
todolist-app/
├── 📁 config/                  # Configuration files
│   ├── auth.php               # Authentication functions
│   ├── config.php             # General configuration
│   └── database.php           # Database connection
├── 📁 controller/             # Business logic controllers
│   ├── categoryController.php # Category CRUD operations
│   ├── dashboardController.php# Dashboard statistics
│   ├── reportController.php   # Report generation
│   ├── settingsController.php # User settings management
│   └── taskController.php     # Task CRUD operations
├── 📁 exports/               # Export functionality
│   ├── excel_export.php     # Excel export feature
│   ├── pdf_export.php       # PDF export feature
│   └── temp/                # Temporary export files
├── 📁 public/                # Public files (entry point)
│   ├── 📁 ajax/              # AJAX endpoints
│   │   ├── liveSearch.php    # Live search functionality
│   │   └── updateProfile.php # Profile update handler
│   ├── 📁 css/               # Stylesheets
│   │   ├── style.css         # Compiled Tailwind CSS
│   │   └── fontawesome-free/ # FontAwesome 6.7.2 icons
│   ├── 📁 js/                # JavaScript files
│   │   ├── app.js            # Responsive sidebar & UI logic
│   │   ├── alert.js          # SweetAlert2 wrapper functions
│   │   ├── auth.js           # Authentication scripts
│   │   ├── jquery.js         # jQuery utilities
│   │   └── jquery-3.7.1.min.js # jQuery library
│   ├── index.php             # Main application router
│   ├── signin.php            # Mobile-optimized login page
│   ├── signup.php            # Mobile-optimized registration
│   └── logout.php            # Secure logout handler
├── 📁 src/                   # Source files
│   └── input.css             # Tailwind CSS source
├── 📁 utility/               # Helper utilities
│   └── databaseUtility.php   # Database operations & encryption
├── 📁 views/                 # View templates
│   ├── 📁 pages/             # Page views
│   │   ├── category.php      # Mobile-responsive category management
│   │   ├── dashboard.php     # Dashboard with statistics
│   │   ├── profile.php       # User profile management
│   │   ├── report.php        # Reports and analytics
│   │   ├── settings.php      # Application settings
│   │   └── task.php          # Mobile-responsive task management
│   └── 📁 templates/         # Reusable templates
│       ├── navbar.php        # Responsive navigation bar
│       └── sidebar.php       # Mobile-friendly sidebar
├── 📁 .github/               # GitHub configuration
│   └── copilot-instructions.md # AI coding agent guidance
├── package.json              # Node.js dependencies for Tailwind
├── composer.json             # PHP dependencies
├── .env.example              # Environment configuration template
└── readme.md                 # This comprehensive documentation
```

---

## 🚀 Quick Start

### Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 7.4+** with MySQLi extension
- **MySQL 8.0+** or MariaDB
- **Web Server** (Apache, Nginx, or Laragon)
- **Composer** (optional, for dependencies)

### Installation Steps

1. **Clone the Repository**

   ```bash
   git clone https://github.com/your-username/todolist-app.git
   cd todolist-app
   ```

2. **Setup Web Server**

   - Place the project in your web server directory:
     - **XAMPP**: `C:/xampp/htdocs/todolist-app`
     - **Laragon**: `C:/laragon/www/todolist-app`
     - **WAMP**: `C:/wamp64/www/todolist-app`

3. **Create Database**

   ```sql
   CREATE DATABASE todolist_db;
   ```

4. **Database Structure**

   The application uses the following database structure:

   ```
   ┌─────────────────────────────────────────────────────────────────┐
   │                        DATABASE SCHEMA                          │
   └─────────────────────────────────────────────────────────────────┘

   ┌──────────────────┐       ┌──────────────────┐       ┌──────────────────┐
   │      users       │       │    category      │       │      task        │
   ├──────────────────┤       ├──────────────────┤       ├──────────────────┤
   │ id (VARCHAR 50)  │       │ id (VARCHAR 50)  │   ┌───│ id (VARCHAR 50)  │
   │ full_name        │       │ name             │   │   │ name             │
   │ username         │       │ created_at       │   │   │ status           │
   │ password         │       └──────────────────┘   │   │ category_id      │──┐
   │ created_at       │                              │   │ created_at       │  │
   └──────────────────┘                              │   └──────────────────┘  │
                                                     │                         │
                                                     └─────────────────────────┘
   ```

5. **Environment Configuration**

   Copy the environment file:

   ```bash
   cp .env.example .env
   ```

   Edit `.env` file with your database credentials:

   ```env
   DB_HOST=localhost
   DB_USERNAME=root
   DB_PASSWORD=
   DB_DATABASE=todolist_db
   SESSION_ENCRYPT_KEY=your-32-character-encryption-key-here
   ```

6. **Run Database Migration**

   Execute the SQL migration script to add user isolation:

   ```sql
   -- Run database_migration.sql in your MySQL client
   -- This adds user_id columns to category and task tables
   ```

7. **Install Dependencies & Build Assets**

   ```bash
   # Install Node.js dependencies for Tailwind CSS
   npm install

   # Build CSS for production
   npm run build

   # Or for development with file watching
   npm run dev
   ```

8. **Access the Application**
   - Open your browser and navigate to:
     - **Local**: `http://localhost/todolist-app/public/`
     - **Laragon**: `http://todolist-app.test/public/`

---

## 🔧 Configuration

1. **Copy Environment File**

   ```bash
   cp .env.example .env
   ```

2. **Edit Environment Variables**

   Open `.env` file and configure your database settings:

   ```env
   # Database Configuration
   DB_HOST=localhost
   DB_USERNAME=root
   DB_PASSWORD=your_password
   DB_DATABASE=todolist_db

   # Application Settings
   APP_NAME="ToDo List App"
   APP_URL=http://localhost/todolist-app

   # Security Settings (IMPORTANT!)
   SESSION_ENCRYPT_KEY=your-32-character-encryption-key-here
   ```

3. **Database Tables**

   The application will automatically create these tables when first accessed:

   - `users` - Store user accounts and authentication
   - `category` - Organize tasks into categories
   - `task` - Main task storage with status and relationships

---

## 🔒 Security Features

This application implements enterprise-level security features to protect user data:

### 🛡️ **Session Security**

- **AES-256-CBC Encryption**: All session data is encrypted before storage
- **Secure Session Management**: User IDs and usernames are never stored in plain text
- **Session Validation**: Multi-layer validation prevents session hijacking
- **Environment-based Keys**: Encryption keys are managed through environment variables

### 👤 **User Data Isolation**

- **User-scoped Queries**: All database operations are filtered by user_id
- **Authorization Checks**: Users can only access their own data
- **Secure Controllers**: All CRUD operations include ownership validation
- **Protected Endpoints**: Session validation on all protected routes

### 🔐 **Authentication Security**

- **Password Hashing**: Passwords are hashed using PHP's secure password_hash()
- **Remember Me Feature**: Secure cookie-based authentication
- **Session Integrity**: Additional session tokens prevent unauthorized access
- **Automatic Logout**: Session cleanup on logout and expiration

### 🚀 **Implementation Benefits**

- ✅ **Data Protection**: Session data encrypted in browser storage
- ✅ **Privacy**: Users cannot access other users' data
- ✅ **Integrity**: Session tampering detection and prevention
- ✅ **Compliance**: Follows security best practices for web applications

For detailed security documentation, see [SECURITY.md](SECURITY.md)

---

## 📱 Mobile Responsiveness Features

This application is built with a **mobile-first approach**, ensuring an excellent experience across all devices:

### 📱 **Mobile Optimization**

- **Responsive Tables** - Horizontal scrolling for data-heavy tables on mobile
- **Touch-Friendly Controls** - Buttons and inputs optimized for touch interaction (44px minimum)
- **Mobile Modal Design** - Full-screen friendly modals with proper spacing
- **Responsive Typography** - Text scales appropriately across devices
- **Flexible Layouts** - Content adapts seamlessly from mobile to desktop

### 🎯 **Screen Breakpoints**

- **Mobile** (< 768px): Optimized for phones with stacked layouts
- **Tablet** (768px - 1024px): Balanced layout for tablet devices
- **Desktop** (> 1024px): Full-featured layout with sidebar navigation

### 💡 **Mobile-Specific Features**

- **Collapsible Sidebar** - Space-efficient navigation on mobile
- **Horizontal Table Scroll** - Smooth table browsing on small screens
- **Responsive Pagination** - Compact pagination controls for mobile
- **Touch Gestures** - Optimized for swipe and tap interactions
- **Mobile-First CSS** - Built with Tailwind CSS mobile-first utilities

---

## 📱 Usage Guide

### 1. **Registration & Login**

- Visit `/public/signup.php` to create a new account
- Login at `/public/signin.php`
- Use "Remember Me" to stay logged in

### 2. **Managing Categories**

- Go to the Categories page
- Add new categories for organizing tasks
- Edit or delete existing categories

### 3. **Managing Tasks**

- Navigate to the Tasks page
- Create tasks and assign them to categories
- Mark tasks as completed
- Use the search feature to find specific tasks

### 4. **Dashboard**

- View overview of your tasks
- Check completion statistics
- Access quick actions

---

## 🛠️ Technology Stack

### **Backend Technologies**

- **PHP 7.4+** - Server-side programming language
- **MySQL 8.0+** - Relational database management
- **MySQLi Extension** - Database connectivity and operations
- **Session Management** - AES-256-CBC encrypted sessions

### **Frontend Technologies**

- **HTML5** - Modern markup language
- **CSS3** - Advanced styling capabilities
- **JavaScript ES6+** - Modern client-side scripting
- **jQuery 3.7.1** - DOM manipulation and AJAX

### **UI Framework & Design**

- **Tailwind CSS v4.1.11** - Utility-first CSS framework with custom build
- **FontAwesome 6.7.2** - Comprehensive icon library
- **SweetAlert2** - Beautiful modal dialogs and notifications
- **Responsive Design** - Mobile-first approach

### **Development Tools**

- **npm** - Package management for frontend dependencies
- **Tailwind CLI** - CSS compilation and optimization
- **Composer** - PHP dependency management (ready for expansion)
- **Node.js** - Build tools and development workflow

### **Security & Performance**

- **AES-256-CBC Encryption** - Session data protection
- **Prepared Statements** - SQL injection prevention
- **CSRF Protection** - Cross-site request forgery prevention
- **Input Sanitization** - XSS attack prevention

---

## 🐛 Troubleshooting

### **Common Issues & Solutions**

#### Database Connection

- **Error**: Database connection failed
- **Solution**:
  - Check your `.env` file configuration
  - Ensure MySQL/MariaDB server is running
  - Verify database name exists and credentials are correct
  - Test connection: `mysql -u username -p database_name`

#### Environment Configuration

- **Error**: Environment file missing or invalid
- **Solution**:
  - Copy `.env.example` to `.env`
  - Configure all required variables
  - Ensure SESSION_ENCRYPT_KEY is exactly 32 characters

#### CSS/JavaScript Issues

- **Error**: Styles not loading or outdated
- **Solution**:
  - Run `npm install` to install dependencies
  - Run `npm run build` to compile Tailwind CSS
  - Clear browser cache (Ctrl+F5)
  - Check console for JavaScript errors

#### Mobile Responsiveness

- **Error**: Layout broken on mobile devices
- **Solution**:
  - Ensure latest CSS is compiled (`npm run build`)
  - Check viewport meta tag is present
  - Test on actual devices, not just browser resize
  - Verify Tailwind responsive classes are working

#### Permission Issues

- **Error**: File permission denied
- **Solution**:
  - Set proper permissions: `chmod 755` for directories, `chmod 644` for files
  - Ensure web server has read access to all files
  - Check ownership: `chown -R www-data:www-data` (Linux/Apache)

#### Performance Issues

- **Error**: Slow page loading
- **Solution**:
  - Enable PHP OPcache
  - Optimize database queries
  - Use browser caching
  - Minify CSS/JS assets

### **Advanced Debugging**

```bash
# Enable PHP error reporting (development only)
# Add to php.ini or .htaccess:
error_reporting(E_ALL);
ini_set('display_errors', 1);

# Check PHP extensions
php -m | grep mysqli

# Test database connection
mysql -u root -p -e "SHOW DATABASES;"
```

---

## 🤝 Contributing

We welcome contributions to make this ToDo List app even better! Here's how you can help:

### **Getting Started**

1. **Fork the Repository**

   ```bash
   git clone https://github.com/your-username/todolist-app.git
   cd todolist-app
   ```

2. **Create a Feature Branch**

   ```bash
   git checkout -b feature/amazing-feature
   ```

3. **Install Dependencies**

   ```bash
   npm install
   cp .env.example .env
   # Configure your .env file
   ```

4. **Make Your Changes**

   - Follow the existing code style and patterns
   - Test your changes thoroughly
   - Ensure mobile responsiveness
   - Add appropriate comments

5. **Test Your Changes**

   ```bash
   # Build CSS
   npm run build

   # Test on multiple devices/browsers
   # Verify functionality works correctly
   ```

6. **Submit a Pull Request**
   - Provide a clear description of your changes
   - Include screenshots for UI changes
   - Reference any related issues

### **Contribution Guidelines**

- 📝 **Code Style**: Follow existing PHP and JavaScript patterns
- 📱 **Mobile First**: Ensure all changes work on mobile devices
- 🔒 **Security**: Maintain security best practices
- 🧪 **Testing**: Test thoroughly before submitting
- 📚 **Documentation**: Update README if adding new features

### **Areas for Contribution**

- 🐛 Bug fixes and improvements
- 📱 Enhanced mobile experience
- 🎨 UI/UX improvements
- 🔧 Performance optimizations
- 📖 Documentation improvements
- 🌐 Internationalization (i18n)
- ⚡ New features and functionality

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

- GitHub: [@xrtvann](https://github.com/xrtvann)
- Email: muhammadirvan011206@gmail.com

---

## 🙏 Acknowledgments

- **[Icons8](https://icons8.com/)** for the beautiful todo list icon
- **[Tailwind CSS](https://tailwindcss.com/)** for the amazing utility-first CSS framework
- **[SweetAlert2](https://sweetalert2.github.io/)** for beautiful modal dialogs and notifications
- **[FontAwesome](https://fontawesome.com/)** for the comprehensive icon library
- **[jQuery](https://jquery.com/)** for reliable DOM manipulation and AJAX
- **[Dribbble](https://dribbble.com/)** design community for mobile table design inspiration
- **[PHP Community](https://www.php.net/community.php)** for continuous support and resources
- **[MySQL](https://www.mysql.com/)** for robust database management
- **[npm](https://www.npmjs.com/)** ecosystem for frontend development tools

### **Special Thanks**

- Modern web development community for responsive design best practices
- Security researchers for encryption and session management insights
- Mobile UX designers for touch-friendly interface guidelines
- Open source contributors who make projects like this possible

---

## 🚀 Future Enhancements

We're constantly working to improve the ToDo List app. Here are some planned features:

- 📊 **Advanced Analytics** - Detailed productivity reports and charts
- 🔔 **Push Notifications** - Task reminders and deadline alerts
- 🌙 **Dark Mode** - Eye-friendly dark theme option
- 📤 **Export Features** - PDF and Excel export functionality
- 🔄 **Task Sync** - Cloud synchronization across devices
- 👥 **Team Collaboration** - Shared tasks and team workspaces
- 🎯 **Goals & Milestones** - Long-term goal tracking
- 🏷️ **Advanced Tagging** - Multiple tags per task
- 🔍 **Advanced Search** - Full-text search with filters
- 📱 **Progressive Web App** - Offline functionality and app-like experience

---

<p align="center">
  <strong>⭐ If this project helped you, please give it a star! ⭐</strong>
</p>
