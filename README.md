# Authentication & Role Management System

A Laravel-based authentication and role management system with Vue.js admin panel. This foundation provides secure user authentication, role-based access control (RBAC), user management, and analytics dashboard. The system is designed to be extended with additional modules for products, services, blog, careers, and more.

## 🛠️ Technical Specifications

### Backend Stack

#### Core Framework
- **Laravel Framework**: ^12.0
- **PHP**: ^8.2
- **Composer**: Dependency management

#### Authentication & Security
- **Laravel Sanctum**: ^4.2 - API token-based authentication
- **Laravel UI**: ^4.6 - Authentication scaffolding

#### Database & ORM
- **Laravel Eloquent ORM**: Built-in ORM for database operations
- **MySQL/PostgreSQL/SQLite**: Supported database systems
- **Yajra Laravel DataTables**: ^12.3 - Advanced data tables with server-side processing
- **Yajra Laravel OCI8**: ^12.0 - Oracle database support

#### File Processing & Media
- **Intervention Image**: ^3.11 - Image manipulation library
- **Intervention Image Laravel**: ^1.5 - Laravel integration for image processing
- **Barryvdh Laravel Snappy**: ^1.0 - PDF generation from HTML

#### Data Export & Import
- **Maatwebsite Excel**: ^3.1 - Excel file import/export functionality

#### API & Integration
- **Guzzle HTTP**: ^7.9 - HTTP client for API requests
- **Inertia.js Laravel**: ^2.0 - Modern monolith approach (SPA-like experience)
- **Tightenco Ziggy**: ^2.4 - Route helper for JavaScript

#### Utilities
- **SimpleSoftwareIO Simple QRCode**: ^4.2 - QR code generation
- **Irazasyed Telegram Bot SDK**: ^3.15 - Telegram bot integration
- **DirectoryTree LDAPRecord Laravel**: ^3.4 - LDAP authentication support

#### Development Tools
- **Laravel Tinker**: ^2.10.1 - REPL for Laravel
- **Laravel Pint**: ^1.18 - Code style fixer
- **Laravel Sail**: ^1.41 - Docker development environment
- **Laravel Pail**: ^1.2.2 - Real-time log viewer
- **OpcodesIO Log Viewer**: ^3.17 - Advanced log viewing interface

#### Testing
- **PHPUnit**: ^11.5.3 - Unit testing framework
- **Mockery**: ^1.6 - Mocking library for testing
- **Nunomaduro Collision**: ^8.6 - Error handler for console/CLI
- **FakerPHP Faker**: ^1.23 - Fake data generator for testing

### Frontend Stack

#### Core Framework
- **Vue.js**: ^3.2.37 - Progressive JavaScript framework
- **Vue Router**: ^4.3.0 - Official router for Vue.js
- **Vite**: ^6.3.5 - Next-generation frontend build tool

#### UI Framework
- **Vuetify**: ^3.5.14 - Material Design component framework
- **Material Design Icons**: ^7.4.47 - Icon library (@mdi/font)

#### State Management
- **Pinia**: ^3.0.4 - Official state management for Vue 3
- **Vuex**: ^4.1.0 - State management pattern library

#### Forms & Validation
- **VForm**: ^2.1.2 - Form validation library for Vue.js

#### Rich Text Editing
- **Quill**: ^2.0.3 - Rich text WYSIWYG editor
- **Vue3 Editor**: ^0.1.1 - Vue 3 wrapper for Quill

#### Data Visualization
- **Chart.js**: ^4.5.1 - Simple yet flexible JavaScript charting library
- **Vue ChartJS**: ^5.3.3 - Vue.js wrapper for Chart.js

#### UI Enhancements
- **Vue SweetAlert2**: ^5.0.5 - Beautiful, responsive, customizable popup boxes
- **Vue3 Progress Bar**: ^1.0.3 - Progress bar component for Vue 3

#### Utilities
- **Axios**: ^1.6.4 - Promise-based HTTP client
- **Moment.js**: ^2.30.1 - Date manipulation library
- **Bootstrap**: ^5.3.6 - CSS framework (for some legacy components)
- **Popper.js**: ^2.11.6 - Tooltip and popover positioning engine
- **Fontsource Prompt**: ^5.0.13 - Self-hosted Prompt font

#### Build Tools
- **Vite**: ^6.3.5 - Fast build tool and dev server
- **Sass**: ^1.89.1 - CSS preprocessor
- **Laravel Vite Plugin**: ^1.0 - Laravel integration for Vite
- **@vitejs/plugin-vue**: ^5.2.4 - Vue.js plugin for Vite

### Development Environment

#### Required Software
- **PHP**: 8.2 or higher
- **Composer**: PHP dependency manager
- **Node.js**: 18+ (for npm)
- **npm**: Package manager for JavaScript
- **MySQL/PostgreSQL/SQLite**: Database system

#### Build Commands
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Development build (with hot reload)
npm run dev

# Production build
npm run build
```

### Architecture

#### Backend Architecture
- **MVC Pattern**: Model-View-Controller architecture
- **RESTful API**: RESTful API design for admin panel
- **Service Layer**: Business logic separation
- **Repository Pattern**: Data access abstraction (where applicable)

#### Frontend Architecture
- **Component-Based**: Vue.js component architecture
- **SPA (Single Page Application)**: Client-side routing with Vue Router
- **API-Driven**: Frontend communicates with backend via REST API
- **State Management**: Centralized state with Pinia/Vuex

#### Security Features
- **CSRF Protection**: Laravel's built-in CSRF protection
- **XSS Protection**: Automatic output escaping
- **SQL Injection Protection**: Eloquent ORM parameter binding
- **Authentication**: Laravel Sanctum token-based authentication
- **Authorization**: Role-Based Access Control (RBAC) with permissions

### Performance Optimizations

#### Backend
- **Query Optimization**: Eloquent eager loading
- **Caching**: Laravel's caching system
- **Database Indexing**: Optimized database queries
- **File Compression**: Image optimization with Intervention Image

#### Frontend
- **Code Splitting**: Lazy loading of routes and components
- **Asset Optimization**: Vite's built-in optimization
- **Image Lazy Loading**: Lazy loading for images
- **Tree Shaking**: Unused code elimination

### Browser Support
- **Modern Browsers**: Chrome, Firefox, Safari, Edge (latest versions)
- **Mobile Browsers**: iOS Safari, Chrome Mobile
- **Responsive Design**: Mobile-first approach with Vuetify

## 🚀 Features

### Core Features

#### Authentication & Security
- **Laravel Sanctum Authentication**: Secure API token-based authentication for admin panel
- **Role-Based Access Control (RBAC)**: Granular permission system with roles and permissions
- **User Management**: Complete user administration with:
  - Multiple role assignment per user
  - Profile information (phone, address, bio, etc.)
  - Avatar management
- **Permission Management**: Fine-grained permission control for all features
- **Login Logging**: Track all login attempts (successful and failed) with IP addresses and user agents

#### Settings Management
- **General Settings**: Site name, tagline, contact information, address
- **Branding Settings**: Logo, favicon, and color scheme customization
- **Social Media Settings**: Social media links and integration
- **SEO Settings**: Default meta tags, descriptions, and keywords
- **Email/SMTP Settings**: Email configuration for notifications

### Admin Panel Features

#### Dashboard & Analytics
- **Analytics Dashboard**: Comprehensive dashboard with:
  - **Statistics Cards**: 
    - Total Visitors with trend indicators
    - Human Visits with percentage breakdown
    - Login activity statistics
  - **Interactive Charts** (Chart.js):
    - Visitor Trends: Line chart showing total and human visits over time
    - Device Distribution: Doughnut chart for desktop, mobile, tablet breakdown
    - Browser Distribution: Bar chart showing top browsers
    - Login Activity: Pie chart for successful vs failed logins
  - **Time Range Selection**: Filter data by 7 days, 30 days, 90 days, or 1 year
  - **Real-time Data**: All statistics update dynamically from database

#### User Management
- **User Administration**:
  - User creation and editing
  - Role assignment (multiple roles per user)
  - Avatar upload
  - Profile information management:
    - Phone number
    - Date of birth
    - Gender (male, female, other)
    - Address, city, state, country, postal code
    - Bio/description
  - Account status management
  - Password reset functionality

#### Role & Permission Management
- **Role Management**:
  - Role creation with permissions
  - Permission assignment
  - System role protection
  - Permission grouping
- **Permission Management**:
  - Permission creation
  - Slug auto-generation
  - Permission grouping by feature
  - Flat and grouped view modes

#### Logs Management
- **Login Logs**:
  - Track all login attempts
  - Filter by status (success/failed)
  - Search by email, IP, user agent
  - Statistics dashboard
  - Time-series data for trends
- **Visitor Logs** (Controller exists, routes pending):
  - Comprehensive visitor tracking
  - Device type detection (Desktop, Mobile, Tablet)
  - Browser and OS detection
  - Bot detection and filtering
  - IP address tracking
  - URL tracking
  - Referer tracking
  - Statistics with device/browser/OS breakdown
  - Top visited pages
  - Time-series data for trends
  - Bulk delete functionality

#### File Upload Management
- **Image Upload**: Single and multiple image uploads
- **File Upload**: Support for PDFs, documents, and other file types
- **File Management**: Delete uploaded files with proper cleanup

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & npm
- MySQL/PostgreSQL/SQLite

## 🔧 Installation

### 1. Clone and Install Dependencies

```bash
git clone <repository-url>
cd s_h_auth_role_fresh
composer install
npm install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Configure your `.env` file:
- Database connection
- `APP_URL`
- Mail settings (for notifications)

### 3. Database Setup

```bash
php artisan migrate
php artisan db:seed
```

This will create:
- All database tables (users, roles, permissions, settings, login_logs, etc.)
- Default roles and permissions
- Admin user account
- Demo data (if DemoDataSeeder is included)

### 4. Build Assets

```bash
npm run dev  # Development
# or
npm run build  # Production
```

### 5. Start Development Server

```bash
php artisan serve
npm run dev
```

Visit:
- Admin panel: `http://localhost:8000/login`
- Dashboard: `http://localhost:8000/dashboard` (after login)

## 🔑 Default User Credentials

The seeder creates an admin user. Check the `AdminUserSeeder` for default credentials.

⚠️ **Change default credentials immediately in production!**

## 📚 API Documentation

### Admin API (`/api/v1/`)

All admin endpoints require authentication via Bearer token (except login and public settings).

**Authentication:**
- `POST /api/v1/auth/login` - Login
- `POST /api/v1/auth/logout` - Logout (requires authentication)
- `GET /api/v1/auth/user` - Get current user (requires authentication)

**Dashboard:**
- `GET /api/v1/dashboard` - Get dashboard statistics and analytics (requires authentication)

**File Upload:**
- `POST /api/v1/upload/image` - Upload single image (requires `manage-media` permission)
- `POST /api/v1/upload/images` - Upload multiple images (requires `manage-media` permission)
- `POST /api/v1/upload/file` - Upload file (PDF, DOC, ZIP, etc.) (requires `manage-media` permission)
- `DELETE /api/v1/upload/image` - Delete image (requires `manage-media` permission)

**User Management:**
- `GET /api/v1/users` - List users (requires `manage-users` permission)
- `POST /api/v1/users` - Create user with profile fields and role assignment (requires `manage-users` permission)
- `GET /api/v1/users/{id}` - Get user (requires `manage-users` permission)
- `PUT /api/v1/users/{id}` - Update user including profile fields and roles (requires `manage-users` permission)
- `DELETE /api/v1/users/{id}` - Delete user (requires `manage-users` permission)
- `GET /api/v1/users/roles` - Get available roles for assignment (requires `manage-users` permission)

**Role & Permission Management:**
- `GET /api/v1/roles` - List roles (requires `manage-roles` permission)
- `POST /api/v1/roles` - Create role (requires `manage-roles` permission)
- `PUT /api/v1/roles/{id}` - Update role (requires `manage-roles` permission)
- `PUT /api/v1/roles/{id}/permissions` - Sync role permissions (requires `manage-roles` permission)
- `DELETE /api/v1/roles/{id}` - Delete role (requires `manage-roles` permission)
- `GET /api/v1/permissions` - List permissions (requires `manage-roles` permission)
- `GET /api/v1/permissions/groups` - Get permission groups (requires `manage-roles` permission)
- `POST /api/v1/permissions` - Create permission (requires `manage-roles` permission)
- `PUT /api/v1/permissions/{id}` - Update permission (requires `manage-roles` permission)
- `DELETE /api/v1/permissions/{id}` - Delete permission (requires `manage-roles` permission)

**Logs & Analytics:**
- `GET /api/v1/login-logs` - List login logs (with pagination, filtering, sorting) (requires `view-login-logs` permission)
- `GET /api/v1/login-logs/statistics` - Get login statistics with time-series data (requires `view-login-logs` permission)
- `GET /api/v1/login-logs/{id}` - Get login log details (requires `view-login-logs` permission)
- `DELETE /api/v1/login-logs/{id}` - Delete login log (requires `view-login-logs` permission)

**Settings:**
- `GET /api/v1/settings` - Get all settings (requires `manage-settings` permission)
- `POST /api/v1/settings` - Update settings (requires `manage-settings` permission)
- `GET /api/v1/public/settings` - Get public settings (no authentication required)

### Public API

Currently, the public API endpoints are not yet implemented. The application uses a Vue.js SPA approach where the frontend is served through the main route and communicates with the admin API endpoints.

**Note:** The routes file references several controllers (products, services, blog, careers, leads, newsletters, about) that are planned but not yet implemented. These will be added in future updates.

## 🎨 Module Configuration

**Note:** Module system is planned for future implementation. Currently, the application focuses on core authentication, user management, and role-based access control.

**Planned Modules:**
- `services` - Services catalog
- `products` - Products catalog
- `portfolio` - Portfolio/Projects
- `blog` - Blog/News
- `faq` - FAQ
- `careers` - Careers & Recruitment
- `booking` - Appointment booking
- `events` - Events & Registrations
- `branches` - Multi-location/Branches
- `ecommerce` - E-commerce
- `multilanguage` - Multi-language support

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/              # Admin API controllers
│   │   │   ├── auth/
│   │   │   │   └── AuthController.php
│   │   │   ├── logs/
│   │   │   │   ├── LoginLogController.php
│   │   │   │   └── VisitorLogController.php
│   │   │   ├── settings/
│   │   │   │   └── SettingController.php
│   │   │   ├── upload/
│   │   │   │   └── UploadController.php
│   │   │   ├── users/
│   │   │   │   ├── PermissionController.php
│   │   │   │   ├── RoleController.php
│   │   │   │   └── UserController.php
│   │   │   └── DashboardController.php
│   │   ├── Controller.php
│   │   └── IndexController.php
│   └── Middleware/            # Authentication & authorization
│       ├── AdminMiddleware.php
│       ├── AdministratorMiddleware.php
│       ├── OnlineCheckerMiddleware.php
│       ├── PermissionMiddleware.php
│       └── TrackVisitors.php
├── Models/                    # Eloquent models
│   ├── LoginLog.php
│   ├── Permission.php
│   ├── Role.php
│   ├── Setting.php
│   └── User.php
├── Providers/
│   └── AppServiceProvider.php
├── Services/
│   └── TelegramNotify.php
├── Support/
│   └── MediaPath.php
└── Traits/
    └── HasImageUrl.php

database/
├── migrations/               # Database migrations
└── seeders/                  # Database seeders
    ├── AdminUserSeeder.php
    ├── DatabaseSeeder.php
    ├── DemoDataSeeder.php
    └── RolePermissionSeeder.php

resources/
├── css/
│   ├── app.css
│   └── common/               # Common CSS files
├── js/
│   ├── components/
│   │   ├── admin/            # Admin panel components
│   │   │   ├── auth/
│   │   │   │   └── AdminLogin.vue
│   │   │   ├── logs/
│   │   │   │   ├── AdminLoginLogs.vue
│   │   │   │   └── AdminVisitorLogs.vue
│   │   │   ├── settings/
│   │   │   │   └── AdminSettings.vue
│   │   │   ├── users/
│   │   │   │   ├── AdminPermissions.vue
│   │   │   │   ├── AdminRoles.vue
│   │   │   │   ├── AdminUsers.vue
│   │   │   │   └── UserProfileDialog.vue
│   │   │   ├── AdminDashboard.vue
│   │   │   └── AdminLayout.vue
│   │   ├── common/
│   │   │   └── RichTextEditor.vue
│   │   ├── app.vue
│   │   └── PageLoader.vue
│   ├── composables/          # Vue 3 composables
│   │   ├── useAuth.js
│   │   ├── useCategories.js
│   │   ├── useProductComparison.js
│   │   ├── useProductFilters.js
│   │   └── useProducts.js
│   ├── mixins/               # Vue mixins
│   │   └── adminPaginationMixin.js
│   ├── plugins/              # Vue plugins
│   │   ├── progressBar.js
│   │   ├── sweetalert.js
│   │   └── vuetify.js
│   ├── stores/               # Pinia stores
│   │   ├── auth.js
│   │   └── README.md
│   ├── utils/                # Utility functions
│   │   ├── axios.config.js
│   │   ├── debounce.js
│   │   ├── formatters.js
│   │   └── uploads.js
│   ├── app.js
│   ├── bootstrap.js
│   └── routes.js             # Vue Router configuration
└── sass/
    ├── _variables.scss
    └── app.scss

routes/
├── api.php                   # API routes
├── console.php               # Artisan command routes
└── web.php                   # Web routes (SPA catch-all)

public/
├── assets/                   # Static assets
├── build/                    # Compiled assets (Vite)
├── uploads/                  # Uploaded files
│   ├── branding/
│   └── users/
└── index.php                 # Application entry point
```

## 🔒 Security

- Admin routes protected with Laravel Sanctum
- Password hashing
- CSRF protection
- XSS protection
- SQL injection protection (Eloquent ORM)
- Role-based access control (RBAC)
- Permission-based route protection
- Bot detection and filtering
- IP address tracking for security

## 📝 Notes

- This is a foundational authentication and role management system
- Core authentication, user management, and RBAC are fully implemented
- Dashboard includes analytics with Chart.js visualizations
- File uploads are stored in `public/uploads/{folder}/` for easy access
- The platform is designed to be modular and configurable
- Additional features (products, services, blog, careers, etc.) are planned but not yet implemented
- The routes file references controllers that are planned for future development
- Visitor logs controller exists but routes are not yet configured

## 🛠️ Development

### Adding a New Feature/Module

1. Create migration: `php artisan make:migration create_[module]_table`
2. Create model: `php artisan make:model [Module]`
3. Create controller: `php artisan make:controller Api/[Module]Controller --api`
4. Add routes to `routes/api.php` with appropriate middleware and permissions
5. Create Vue components in `resources/js/components/admin/[module]/`
6. Add routes to `resources/js/routes.js`
7. Create/update seeders if needed

### Testing

```bash
php artisan test
```

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.
