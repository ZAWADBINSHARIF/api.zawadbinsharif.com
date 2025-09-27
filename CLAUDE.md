# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 backend application with Filament 3 admin panel for a portfolio website. The application manages profiles, projects, and contacts through a modern PHP admin interface.

## Technology Stack

- **Framework**: Laravel 12
- **PHP Version**: ^8.2
- **Admin Panel**: Filament 3.3
- **Database**: SQLite (development), configurable for production
- **Frontend Build**: Vite with Tailwind CSS 4
- **Testing**: PHPUnit 11

## Development Commands

### Starting the Application
```bash
# Full development environment (server, queue, logs, and vite)
composer run dev

# Individual services
php artisan serve --port=4000  # Laravel server
npm run dev                     # Vite dev server
php artisan queue:listen        # Queue worker
php artisan pail               # Log viewer
```

### Build and Asset Management
```bash
npm run build              # Build frontend assets
php artisan migrate        # Run database migrations
php artisan db:seed        # Seed the database
```

### Testing
```bash
composer test              # Run all tests
php artisan test          # Run tests with Laravel wrapper
php artisan test --filter TestName  # Run specific test
```

### Code Quality
```bash
vendor/bin/pint           # Laravel code formatter (Pint)
```

## Architecture

### Core Models
- **Profile**: Portfolio owner information with file uploads (image/resume)
- **Project**: Portfolio projects (implementation pending)
- **Contact**: Contact form submissions
- **User**: Admin users for Filament panel

### Filament Admin Panel
- Located at root path `/` with authentication
- Resources auto-discovered from `app/Filament/Resources/`
- Custom pages in `app/Filament/Pages/`
- AdminPanelProvider configures the panel at `app/Providers/Filament/AdminPanelProvider.php`

### File Storage
- Uses Laravel's Storage facade with public disk
- File paths managed via `StoragePath` enum
- Automatic cleanup of old files on model updates/deletions

### Key Directories
- `app/Models/` - Eloquent models
- `app/Filament/` - Admin panel resources and pages
- `database/migrations/` - Database schema
- `app/Enums/` - Application enums (e.g., StoragePath)

## Database Configuration

Development uses SQLite with in-memory database for testing. Production database configured via `.env` file.

## Environment Variables

Copy `.env.example` to `.env` and configure. Key variables:
- Database connection settings
- Application URL and port
- Storage disk configuration
- Queue and cache drivers

## Filament Customization

The admin panel theme uses Amber as primary color. Resources and pages are auto-discovered. Profile page (`app/Filament/Pages/Profile.php`) is a custom single-record page for managing portfolio owner information.