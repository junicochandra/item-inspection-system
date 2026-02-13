<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Inspection App

A simple web application for managing item inspections.  
This app helps you track items, inspection statuses, scopes of work, and related details.

## Installation Guide

Follow these steps to set up the application:

```bash
# 1. Install PHP dependencies
composer install
# Installs all required PHP packages for the application.

# 2. Build frontend assets
npm run build
# Compiles CSS, JS, and other frontend files.

# 3. Run database migrations
php artisan migrate:fresh
# Creates all database tables from scratch.
# WARNING: This will erase existing data if the database is not empty.

# 4. Import initial data via Postman
# The file is in the import-file folder. Make sure all imports complete successfully before proceeding.
# Use the provided Postman collection to import essential reference data:
#    - Import Item Categories
#    - Import Inspection Statuses
#    - Import Scope of Work
#    - Import Scope of Work Items

# 5. Seed the database
php artisan db:seed
# Fills the database with initial sample data for testing and basic usage.

# 6. Run unit tests
php artisan test --testsuite=Unit
# Executes all unit tests to verify individual functions and classes work as expected.

# 7. Running Laravel Server
php artisan serve
# The server will be running and accessible at the following URL:
http://localhost/inspections

```
