# AI Coding Agent Instructions for ToDo List App

## Architecture Overview

This is a **multi-user PHP ToDo application** with a **page-based MVC architecture** using native PHP, MySQL, and Tailwind CSS. Key characteristics:

- **Single Entry Point**: `public/index.php` handles all authenticated pages via `?page=` routing
- **User Isolation**: All data (categories/tasks) are filtered by `user_id` from session
- **Global Connection**: Uses `global $connection` pattern throughout controllers
- **ID Generation**: Custom prefixed IDs (`CTRGY-001`, `TSK-001`) generated in controllers
- **Encrypted Sessions**: User data stored in encrypted session variables for security

## Critical Patterns

### Database Layer (`utility/databaseUtility.php`)

- **CRUD Functions**: `read()`, `insert()`, `edit()`, `delete()` - always use these, never raw mysqli
- **User Filtering**: `getCurrentUserId()` gets session user, `pagination()` ALWAYS filters by `user_id` by default
- **Escape Everything**: Always `mysqli_real_escape_string()` user inputs
- **Pagination**: Fixed parameter order: `show($limit, $offset)` and `LIMIT $offset, $limit` in SQL

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

// Show function pattern:
function show($limit = 10, $offset = 0) {
    $userId = getCurrentUserId();
    if (!$userId) return [];

    $query = "SELECT * FROM table WHERE user_id = '$userId' ORDER BY created_at DESC LIMIT $offset, $limit";
    return read($query);
}
```

### Authentication & Security

- **Encrypted Sessions**: `$_SESSION['user_id_hash']`, `$_SESSION['username_hash']`, `$_SESSION['full_name_hash']`
- **Session Validation**: `validateSession()` checks integrity of encrypted session data
- **User Context**: Always use `getCurrentUserId()`, `getCurrentUsername()`, `getCurrentFullName()`
- **Session Guards**: Every page checks `validateSession()` in `index.php`

## Live Search Implementation

**AJAX Pattern**: Uses jQuery `data-search="livesearch"` attribute

```javascript
// Triggers on keyup, loads results into #table-body
$("#table-body").load(
  "ajax/liveSearch.php?page=" + page + "&keyword=" + keyword
);
```

**Backend**: `public/ajax/liveSearch.php` handles both category and task search, returns HTML table rows with empty state handling

## Empty State & Pagination Patterns

**Empty State Handling**: All tables include empty state messages:

```php
<?php if (empty($items)): ?>
    <tr>
        <td colspan="4" class="text-center py-12 text-gray-500">
            <div class="flex flex-col items-center">
                <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                <p class="text-lg font-medium">No items found</p>
                <p class="text-sm">Start by creating your first item</p>
            </div>
        </td>
    </tr>
<?php endif; ?>
```

**Conditional Pagination**: Only show pagination when data exists:

```php
<?php if (!empty($items) && $pagination['amountOfData'] > 0): ?>
    <div class="pagination-wrapper">
        <!-- pagination controls -->
    </div>
<?php endif; ?>
```

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
- `category(id, name, user_id, created_at)`
- `task(id, name, status, category_id, user_id, created_at)`

## Frontend Patterns

**CSS Framework**: Tailwind CSS v4.1.11 with custom build pipeline
**Icons**: FontAwesome 6.7.2 included via npm
**Modals**: Custom modal system with `showEditModal()`, `showConfirmationDelete()` functions
**Alerts**: SweetAlert2 via session flash messages (`$_SESSION['alert_type']`, `$_SESSION['alert_message']`)
**Dropdowns**: Custom dropdown components with `data-dropdown` attributes

### JavaScript Patterns

- **Responsive Sidebar**: `toggleSidebar()` handles mobile/desktop breakpoints at 1024px
- **User Menu**: `toggleUserMenu()` with outside-click dismissal
- **SweetAlert Wrappers**: `showSuccessAlert()`, `showErrorAlert()`, `showConfirmationDelete()` in `alert.js`
- **Task Categories**: `selectTaskCategory()` function handles category selection logic
- **Mobile-First**: Sidebar starts hidden on mobile (`-translate-x-full` class)

## Build Commands

```bash
# Development with file watching
npm run dev

# Production build (minified)
npm run build
```

## Key Files to Understand

- `public/index.php` - Main router and form handler with POST-redirect-GET pattern
- `utility/databaseUtility.php` - All database operations, user sessions, pagination, AES-256-CBC encryption
- `controller/categoryController.php` - CRUD pattern example with user isolation and ID generation
- `controller/dashboardController.php` - Statistics aggregation with `getDashboardStats()` function
- `public/ajax/liveSearch.php` - AJAX search with empty state handling and session validation
- `public/js/app.js` - Responsive sidebar logic and UI interactions
- `public/js/alert.js` - SweetAlert2 wrapper functions for consistent notifications
- `views/pages/` - Page templates with embedded PHP and comprehensive empty states
- `config/database.php` - Database connection with environment variables
- `exports/` - Directory for PDF/Excel export functionality (placeholder structure)

## Environment Setup

```bash
# Copy environment configuration
cp .env.example .env

# Install dependencies
npm install

# Build CSS
npm run build
```

## Critical Dependencies

- **Tailwind CSS**: Custom build pipeline for styling with `npm run dev`/`npm run build`
- **SweetAlert2**: User notifications and confirmations via wrapper functions
- **FontAwesome**: Icon library (6.7.2) with consistent icon patterns
- **jQuery**: DOM manipulation for live search and AJAX interactions
- **MySQL**: Database with user isolation patterns and AES-256-CBC session encryption

## Development Workflow

- **Session Management**: Must call `session_start()` in every file
- **Database Connection**: `connectDatabase()` from `config/database.php`
- **User Context**: `getCurrentUserId()` for all user-specific operations
- **Form Processing**: POST-redirect-GET pattern prevents duplicate submissions
- **Mobile Responsive**: 1024px breakpoint for sidebar behavior changes
