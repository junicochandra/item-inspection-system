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

# Inspection System Testing Guide

This document provides steps for testing the main features of the inspection system.

## 1. Inspection Record

The Inspection Record page has 3 tabs:

- **Open** - displays all inspections that are still open.
- **For Review** - displays inspections that are completed but need review.
- **Open** - displays inspections that are fully completed.

Each tab shows data according to the inspection status.

## 2. Create Inspection (Add Data)

The Create Inspection page is used to add a new inspection record. The form has 3 sections:

### a. Inspection Information

- All dropdown fields are populated from the database.
- The Scope of Work field is linked with Scope Included. Changing the Scope of Work will automatically update the Scope Included values.
- Scope of Work & Scope Included data can be inserted via Excel files (.xlsx or .csv) using Postman.

### b. Order Information

- The Lot Selection field affects the following fields: Allocation, Owner, Condition.
    - Selecting a Lot and Allocation will adjust the available options for Owner and Condition.
- Each Lot has its own Available Qty.
    - Switching Lot will display the corresponding stock.
- The Qty Required field represents the number of items used for inspection.
    - This value cannot exceed the Available Qty.

### c. Note to Yard

- This field is used to add notes or remarks during the inspection process.

## 3. Update Status

- The inspection status determines whether the data can still be modified.
- If the status is Open:
    - The inspection form can still be edited.
- If the status is changed to For Review:
    - The inspection data can no longer be edited.
    - The inspection is waiting for approval.
    - An Approve button will appear.

## 4. What Happens When "Approve" is Clicked

- When the Approve button is clicked:
    - The inspection status changes to Complete.
    - The inspection can no longer be edited or modified.
    - The stock quantity in the Lots table will be reduced based on the Qty Required.
    - Two new fields will appear in the Inspection Detail page:
        - Order No
        - Service Description

These fields indicate that an order record has been generated to document the stock deduction.
