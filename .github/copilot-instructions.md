# AI Coding Agent Instructions for ToDo List App

## Architecture Overview

This is a **multi-user PHP ToDo application** with a **page-based MVC architecture** using native PHP, MySQL, and Tailwind CSS. Key characteristics:

- **Single Entry Point**: `public/index.php` handles all authenticated pages via `?page=` routing
- **User Isolation**: All data (categories/tasks) are filtered by `user_id` from session
- **Global Connection**: Uses `global $connection` pattern throughout controllers
- **ID Generation**: Custom prefixed IDs (`CTRGY-001`, `TSK-001`) generated in controllers

## Critical Patterns

### Database Layer (`utility/databaseUtility.php`)

- **CRUD Functions**: `read()`, `insert()`, `edit()`, `delete()` - always use these, never raw mysqli
- **User Filtering**: `getCurrentUserId()` gets session user, `pagination()` accepts `$userIdColumn` parameter
- **Escape Everything**: Always `mysqli_real_escape_string()` user inputs

### Controller Pattern

```php
// Controllers follow this structure:
function store() {
    global $connection;
    $userId = getCurrentUserId(); // Always check user first
    if (!$userId) return 0;

    $data = ['id' => $id, 'name' => $name, 'user_id' => $userId];
    insert('table', $data);
    return mysqli_affected_rows($connection);
}
```

### Authentication Flow

- **Session Keys**: `$_SESSION['login']`, `$_SESSION['user_id']`, `$_SESSION['username']`
- **Session Guards**: Every page checks `$_SESSION['login']` in `index.php`
- **User Context**: Controllers use `getCurrentUserId()` for data isolation

## Live Search Implementation

**AJAX Pattern**: Uses jQuery `data-search="livesearch"` attribute

```javascript
// Triggers on keyup, loads results into #table-body
$("#table-body").load(
  "ajax/liveSearch.php?page=" + page + "&keyword=" + keyword
);
```

**Backend**: `public/ajax/liveSearch.php` handles both category and task search, returns HTML table rows

## Page Routing System

**URL Structure**: `index.php?page=dashboard|category|task|settings|report`

**Form Handling**: POST requests processed in `index.php` before view rendering:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($page === 'category' && isset($_POST['saveCategory'])) {
        require_once '../controller/categoryController.php';
        $result = store();
        // Set session alerts, then redirect
    }
}
```

## Database Schema

**User Isolation**: Every table has `user_id` foreign key

- `users(id, username, password, full_name)`
- `category(id, name, user_id)`
- `task(id, name, status, category_id, user_id)`

**Migration**: Use `migrate.php` for existing databases, `database.sql` for fresh installs

## Frontend Patterns

**Modals**: Custom modal system with `showEditModal()`, `showConfirmationDelete()` functions
**Alerts**: SweetAlert2 via session flash messages (`$_SESSION['alert_type']`, `$_SESSION['alert_message']`)
**Dropdowns**: Custom dropdown components with `data-dropdown` attributes

## Key Files to Understand

- `public/index.php` - Main router and form handler
- `utility/databaseUtility.php` - All database operations
- `controller/categoryController.php` - CRUD pattern example
- `public/ajax/liveSearch.php` - AJAX search implementation
- `views/pages/` - Page templates with embedded PHP


# Environment setup
cp .env.example .env
```

## Critical Dependencies

- **Session Management**: Must call `session_start()` in every file
- **Database Connection**: `connectDatabase()` from `config/database.php`
- **User Context**: `getCurrentUserId()` for all user-specific operations
