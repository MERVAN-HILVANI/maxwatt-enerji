# Maxwatt Enerji - Solar Systems E-Commerce System

![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)

## 📋 About The Project

Maxwatt Enerji is a full-featured e-commerce system for solar system products. It includes a complete admin panel, customer-facing store, order management, payment processing, refund system, and much more.

## 🚀 Features

### 🛒 Customer Side
- ✅ Customer registration & login (separate from admin)
- ✅ Email verification system
- ✅ Product listing with filtering & sorting
- ✅ Product detail page with photo gallery
- ✅ Shopping cart (add, remove, update quantity)
- ✅ Order placement with full address system (Province/District/Neighborhood API)
- ✅ Multiple payment methods (Cash on Delivery, Bank Transfer, Credit Card)
- ✅ Bank transfer with IBAN display & payment receipt upload
- ✅ Order tracking & history
- ✅ Order cancellation (pending orders only)
- ✅ Refund request system
- ✅ Product reviews & star ratings
- ✅ Favorites list
- ✅ Customer profile management
- ✅ Password change with security requirements

### ⚙️ Admin Panel
- ✅ Separate admin authentication system
- ✅ Dashboard with live statistics (revenue, orders, customers)
- ✅ Product management (add, edit, delete)
- ✅ Multiple photo upload with primary photo selection
- ✅ Instant photo upload (AJAX)
- ✅ Photo gallery with lightbox
- ✅ Category management
- ✅ Order management with status tracking
- ✅ Cargo company & tracking number management
- ✅ Payment receipt approval/rejection
- ✅ Cash on delivery approval with ID verification
- ✅ Refund request management
- ✅ Revenue tracking (refunds deducted from revenue)
- ✅ Admin profile management

### 🔒 Security
- ✅ Separate guards for admin and customers
- ✅ Email verification required for orders
- ✅ Cash on delivery: TC ID & birth date required
- ✅ Cash on delivery: 5,000₺ limit (1,000₺ for new customers)
- ✅ Admin approval required for cash on delivery orders
- ✅ Bank transfer: payment receipt required
- ✅ Strong password policy (8+ chars, uppercase, lowercase, number)
- ✅ All required fields validated

### 💰 VAT & Pricing
- ✅ VAT calculation (1% - 20%)
- ✅ VAT-inclusive price auto-calculation
- ✅ Discount management
- ✅ Warranty period tracking (1-10 years)

## 🛠️ Technologies Used

| Technology | Version |
|------------|---------|
| Laravel | 12.x |
| PHP | 8.2 |
| MySQL | 8.0 |
| Bootstrap | 5.3 |
| Blade | - |
| JavaScript | ES6 |

## ⚙️ Installation

### Requirements
- PHP 8.2+
- Composer
- MySQL
- XAMPP or similar server

### Steps

1. **Clone the repository:**
```bash
git clone https://github.com/MERVAN-HILVANI/maxwatt-enerji.git
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
DB_DATABASE=laravelproject
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations and seeders:**
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

6. **Create admin user:**
```bash
php artisan tinker
App\Models\Admin::create(['name' => 'Admin User', 'email' => 'admin@maxwattenerji.com', 'password' => bcrypt('Admin123!')])
```

7. **Start the server:**
```bash
php artisan serve
```

8. **Open in browser:**
```
http://localhost:8000
```

## 🔐 Login Information

### Admin Panel
| Field | Value |
|-------|-------|
| URL | /login |
| Email | admin@maxwattenerji.com |
| Password | Admin123! |

### Customer Site
| Field | Value |
|-------|-------|
| URL | /musteri/giris |
| Registration | /musteri/kayit |

## 📁 Project Structure

```
LaravelProject/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AdminHomeController.php
│   │   │   │   ├── AdminProductController.php
│   │   │   │   ├── AdminOrderController.php
│   │   │   │   └── CategoryController.php
│   │   │   ├── Auth/
│   │   │   │   └── CustomerAuthController.php
│   │   │   ├── AuthController.php
│   │   │   ├── ShopController.php
│   │   │   ├── CartController.php
│   │   │   └── OrderController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── Admin.php
│       ├── Category.php
│       ├── Product.php
│       ├── ProductImage.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Cart.php
│       ├── Favorite.php
│       ├── Review.php
│       ├── Refund.php
│       └── User.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── products/
│       │   ├── categories/
│       │   └── orders/
│       ├── shop/
│       │   └── auth/
│       └── layouts/
└── routes/
    └── web.php
```

## 👨‍💻 Developer

- **Student:** Mohammad Marwan Helwani
- **Student ID:** 20222022508
- **Course:** Advanced Web Programming
- **University:** Nişantaşı University
