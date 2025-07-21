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

- 🔐 **User Authentication** - Secure registration and login system
- 📋 **Task Management** - Create, edit, delete, and mark tasks as complete
- 🗂️ **Categories** - Organize tasks with custom categories
- 🔍 **Live Search** - Real-time search across tasks and categories
- 📊 **Dashboard** - Overview of your tasks and progress
- 📱 **Responsive Design** - Works on desktop, tablet, and mobile
- 🎨 **Modern Interface** - Clean and intuitive user experience
- ⚡ **Fast Performance** - Smooth and responsive interactions

---

## 🏗️ Project Structure

```
todolist-app/
├── 📁 config/                  # Configuration files
│   ├── auth.php               # Authentication functions
│   ├── config.php             # General configuration
│   └── database.php           # Database connection
├── 📁 controller/             # Business logic controllers
│   ├── categoryController.php # Category operations
│   └── taskController.php     # Task operations
├── 📁 public/                 # Public files (entry point)
│   ├── 📁 ajax/              # AJAX endpoints
│   │   └── liveSearch.php    # Live search functionality
│   ├── 📁 css/               # Stylesheets
│   │   ├── style.css         # Main stylesheet
│   │   └── fontawesome-free/ # FontAwesome icons
│   ├── 📁 js/                # JavaScript files
│   │   ├── app.js            # Main application logic
│   │   ├── alert.js          # SweetAlert configurations
│   │   ├── auth.js           # Authentication scripts
│   │   └── jquery.js         # jQuery utilities
│   ├── index.php             # Main application entry
│   ├── signin.php            # Login page
│   └── signup.php            # Registration page
├── 📁 utility/               # Helper utilities
│   └── databaseUtility.php   # Database utility functions
├── 📁 views/                 # View templates
│   ├── 📁 pages/             # Page views
│   │   ├── category.php      # Category management page
│   │   ├── dashboard.php     # Dashboard page
│   │   ├── report.php        # Reports page
│   │   ├── settings.php      # Settings page
│   │   └── task.php          # Task management page
│   └── 📁 templates/         # Reusable templates
│       ├── navbar.php        # Navigation bar
│       └── sidebar.php       # Sidebar navigation
├── package.json              # Node.js dependencies
└── readme.md                 # This file
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
   ```

6. **Access the Application**
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
   ```

3. **Database Tables**

   The application will automatically create these tables when first accessed:

   - `users` - Store user accounts and authentication
   - `category` - Organize tasks into categories
   - `task` - Main task storage with status and relationships

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

- **Backend**: PHP 7.4+
- **Database**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript
- **UI Framework**: Tailwind CSS
- **Icons**: FontAwesome
- **Alerts**: SweetAlert2

---

## 🐛 Troubleshooting

- **Database connection error**:
  - Check your `.env` file configuration
  - Ensure database server is running
  - Verify database name exists
- **Environment file missing**: Copy `.env.example` to `.env` and configure it
- **Permission issues**: Ensure proper file permissions on your web server
- **Page not loading**: Make sure you're accessing through `/public/` directory
- **Tables not created**: Check database credentials and permissions

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**Your Name**

- GitHub: [@xrtvann](https://github.com/your-username)
- Email: muhammadirvan011206@gmail.com

---

## 🙏 Acknowledgments

- **Icons8** for the beautiful todo list icon
- **Tailwind CSS** for the amazing utility-first CSS framework
- **SweetAlert2** for beautiful modal dialogs
- **FontAwesome** for comprehensive icon library
- **PHP Community** for continuous support and resources

---

<p align="center">
  <strong>⭐ If this project helped you, please give it a star! ⭐</strong>
</p>
