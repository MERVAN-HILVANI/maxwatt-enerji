# \# Maxwatt Enerji - Solar Systems E-Commerce System

!\[Laravel](https://img.shields.io/badge/Laravel-12.x-red)
!\[PHP](https://img.shields.io/badge/PHP-8.2-blue)
!\[MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
!\[Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)

## 📋 About The Project

Maxwatt Enerji is an e-commerce admin panel for managing solar system products. It includes features such as product and category management, multiple photo uploads, VAT calculation, and warranty tracking.

## 🚀 Features

* ✅ Admin login and authorization system
* ✅ Category management (add, edit, delete)
* ✅ Product management (add, edit, delete)
* ✅ Multiple photo upload with primary photo selection
* ✅ Instant photo upload (AJAX)
* ✅ Photo gallery (lightbox with arrow navigation)
* ✅ KDV calculation (1% - 20%)
* ✅ Warranty period tracking (1 - 10 years)
* ✅ Stock and discount management
* ✅ Product status management (Active/Passive)
* ✅ Responsive design with Bootstrap 5
* ✅ Maxwatt blue theme

## 🛠️ Technologies Used

|Technology|Version|
|-|-|
|Laravel|12.x|
|PHP|8.2|
|MySQL|8.0|
|Bootstrap|5.3|
|Blade|-|

## ⚙️ Installation

### Requirements

* PHP 8.2+
* Composer
* MySQL
* XAMPP or similar server

### Steps

1. **Clone the repository:**

```bash
git clone https://github.com/USERNAME/maxwatt-enerji.git
cd maxwatt-enerji
```

2. **Install dependencies:**

```bash
composer install
```

3. **Create the .env file:**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database settings (.env):**

```
DB\\\_DATABASE=laravelproject
DB\\\_USERNAME=root
DB\\\_PASSWORD=
```

5. **Run migrations and seeders:**

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

6. **Start the server:**

```bash
php artisan serve
```

7. **Open in browser:**

```
http://localhost:8000
```

## 🔐 Admin Login

|Field|Value|
|-|-|
|Email|admin@maxwattenerji.com|
|Password|12345|

## 📁 Project Structure

```
LaravelProject/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Admin/
│   │   │       ├── AdminHomeController.php
│   │   │       ├── AdminProductController.php
│   │   │       └── CategoryController.php
│   │   └── Middleware/
│   └── Models/
│       ├── Category.php
│       ├── Product.php
│       ├── ProductImage.php
│       └── User.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── products/
│       │   └── categories/
│       └── layouts/
└── routes/
    └── web.php
```

## 👨‍💻 Developer

* **Student:** Mohammad Marwan Helwani
* **Student ID:** 20222022508
* **Course:** Advanced Web Programming
* **University:** Nişantaşı University

