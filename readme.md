<p align="center">
  <img src="https://img.icons8.com/fluency/96/000000/todo-list.png" alt="ToDo List Icon" width="96" height="96"/>
</p>

<h1 align="center">🚀 ToDo List App</h1>

<p align="center">
  <strong>A modern, mobile-first ToDo List web application with advanced security and responsive design</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-7.4+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/TailwindCSS-4.1.11-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat-square&logo=javascript&logoColor=black" alt="JavaScript">
  <img src="https://img.shields.io/badge/Mobile-Responsive-28a745?style=flat-square" alt="Mobile Responsive">
</p>

---

## 📋 About

This is a **full-featured ToDo List application** built with modern web technologies. It features a complete user authentication system, task and category management, real-time search, and a mobile-first responsive design. The application implements enterprise-level security with AES-256-CBC encryption and user data isolation.

### 🎯 **Key Highlights**

- **Mobile-First Design** - Optimized for all devices with touch-friendly interface
- **Secure Authentication** - AES-256-CBC encrypted sessions with user isolation
- **Real-Time Search** - AJAX-powered live search across tasks and categories
- **Modern UI** - Built with Tailwind CSS, SweetAlert2, and FontAwesome icons
- **Performance Optimized** - Fast loading with efficient database queries

---

## ✨ Features

### 🔐 **Authentication & Security**

- Complete user registration and login system
- AES-256-CBC encrypted session management
- User data isolation (each user sees only their data)
- Remember me functionality with secure cookies
- Protection against SQL injection, XSS, and CSRF attacks

### 📋 **Task Management**

- Create, edit, delete, and mark tasks as complete
- Auto-generated task IDs (TSK-001, TSK-002, etc.)
- Assign tasks to custom categories
- Real-time status updates and date tracking

### 🗂️ **Category System**

- Organize tasks with custom categories
- Auto-generated category IDs (CTRGY-001, etc.)
- Visual category icons and management interface
- Category-task relationship mapping

### 🔍 **Live Search & Filtering**

- Real-time AJAX search across tasks and categories
- Instant results as you type
- Responsive search optimized for all devices
- Advanced filtering capabilities

### 📱 **Mobile-First Responsive Design**

- **Touch-friendly interface** with 44px minimum touch targets
- **Horizontal scrolling tables** for mobile data viewing
- **Responsive modals** optimized for small screens
- **Collapsible sidebar** navigation for mobile
- **Adaptive layouts** that work on phones, tablets, and desktops

### 🎨 **Modern User Interface**

- Built with Tailwind CSS v4.1.11 custom build pipeline
- SweetAlert2 for beautiful notifications
- FontAwesome 6.7.2 comprehensive icon library
- Smooth animations and hover effects
- Clean empty states and loading indicators

### ⚡ **Performance & Optimization**

- AJAX-powered interactions for fast user experience
- Optimized database queries with pagination
- CSS build pipeline with Tailwind compilation
- Efficient session and data management

---

## 🚀 Installation

### Prerequisites

- PHP 7.4+ with MySQLi extension
- MySQL 8.0+ or MariaDB
- Web Server (Apache, Nginx, or Laragon)
- Node.js and npm for building CSS assets

### Quick Setup

1. **Clone the repository:**

   ```bash
   git clone https://github.com/xrtvann/todolist-app.git
   cd todolist-app
   ```

2. **Install dependencies:**

   ```bash
   npm install
   ```

3. **Setup environment:**

   ```bash
   cp .env.example .env
   ```

   Edit `.env` with your database credentials:

   ```env
   DB_HOST=localhost
   DB_USERNAME=root
   DB_PASSWORD=your_password
   DB_DATABASE=todolist_db
   SESSION_ENCRYPT_KEY=your-32-character-encryption-key
   ```

4. **Create database:**

   ```sql
   CREATE DATABASE todolist_db;
   ```

5. **Build CSS assets:**

   ```bash
   npm run build
   ```

6. **Setup web server:**

   - **XAMPP**: Place in `C:/xampp/htdocs/todolist-app`
   - **Laragon**: Place in `C:/laragon/www/todolist-app`
   - **WAMP**: Place in `C:/wamp64/www/todolist-app`

7. **Access the application:**
   - Local: `http://localhost/todolist-app/public/`
   - Laragon: `http://todolist-app.test/public/`

### Database Schema

The application automatically creates these tables:

```sql
-- Users table
users (id, username, password, full_name, created_at)

-- Categories table
category (id, name, user_id, created_at)

-- Tasks table
task (id, name, status, category_id, user_id, created_at)
```

### Development Commands

```bash
# Development with file watching
npm run dev

# Production build
npm run build

# Clear cache (if needed)
php -r "opcache_reset();"
```

---## 🛠️ Technology Stack

**Backend:**

- **PHP 7.4+** - Server-side programming with MySQLi extension
- **MySQL 8.0+** - Relational database with user isolation
- **AES-256-CBC Encryption** - Secure session management

**Frontend:**

- **HTML5 & CSS3** - Modern web standards
- **JavaScript ES6+** - Client-side functionality
- **jQuery 3.7.1** - DOM manipulation and AJAX

**UI Framework:**

- **Tailwind CSS v4.1.11** - Utility-first CSS with custom build pipeline
- **FontAwesome 6.7.2** - Comprehensive icon library
- **SweetAlert2** - Beautiful modal dialogs and notifications

**Development Tools:**

- **npm & Node.js** - Frontend dependency management and build tools
- **Tailwind CLI** - CSS compilation and optimization
- **Git** - Version control

---

## � Usage

1. **Sign up** for a new account or **sign in** with existing credentials
2. **Create categories** to organize your tasks
3. **Add tasks** and assign them to categories
4. **Mark tasks as complete** when finished
5. **Use live search** to quickly find specific tasks
6. **View dashboard** for task statistics and overview

---

## 🎯 Project Structure

```
todolist-app/
├── 📁 config/           # Configuration files
├── 📁 controller/       # Business logic (CRUD operations)
├── 📁 public/           # Entry point and assets
│   ├── 📁 css/         # Compiled Tailwind CSS
│   ├── 📁 js/          # JavaScript files
│   └── index.php       # Main application router
├── 📁 utility/         # Database utilities and encryption
├── 📁 views/           # UI templates and pages
├── 📁 .github/         # AI coding guidelines
├── package.json        # Node.js dependencies
└── .env.example        # Environment configuration
```

---

## 🐛 Troubleshooting

**Database Connection Issues:**

- Verify `.env` configuration matches your database setup
- Ensure MySQL service is running
- Check database exists: `CREATE DATABASE todolist_db;`

**CSS Not Loading:**

- Run `npm install` then `npm run build`
- Clear browser cache (Ctrl+F5)
- Verify file permissions

**Mobile Layout Issues:**

- Ensure latest CSS is compiled
- Test on actual devices, not just browser resize
- Check console for JavaScript errors

---

## 🤝 Contributing

1. Fork the repository: `git clone https://github.com/xrtvann/todolist-app.git`
2. Create a feature branch: `git checkout -b feature/new-feature`
3. Make your changes and test thoroughly
4. Build CSS: `npm run build`
5. Submit a pull request with clear description

**Guidelines:**

- Follow existing code patterns
- Ensure mobile responsiveness
- Test on multiple devices/browsers
- Update documentation if needed

---

## 📄 License

This project is licensed under the **MIT License**.

---

## 👨‍💻 Author

**Muhammad Irvan**

- GitHub: [@xrtvann](https://github.com/xrtvann)
- Email: muhammadirvan011206@gmail.com

---

## 🙏 Acknowledgments

- [Tailwind CSS](https://tailwindcss.com/) - Utility-first CSS framework
- [SweetAlert2](https://sweetalert2.github.io/) - Beautiful modal dialogs
- [FontAwesome](https://fontawesome.com/) - Icon library
- [Icons8](https://icons8.com/) - Application icon
- [Dribbble](https://dribbble.com/) - Mobile design inspiration

---

<p align="center">
  <strong>⭐ If this project helped you, please give it a star! ⭐</strong>
</p>
