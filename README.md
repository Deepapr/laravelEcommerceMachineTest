# Laravel Ecommerce Machine Test

## Features Implemented
- Login with username & password
- Product CRUD
- Category / Color / Size masters
- Image upload & conversion to WEBP
- Product import via Excel (image URL → WEBP)
- Simple cart & checkout (basic flow)

## Tech Stack
- Laravel
- MySQL
- Laravel Excel
- image conversion

## Setup Instructions
1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Set DB credentials
5. Run `php artisan key:generate`
6. Run `php artisan migrate`
7. Run `php artisan storage:link`
8. Start server using `php artisan serve`

## Excel Import Format
product_name,category,color,size,price,quantity,description,sku,image_url
Make an existing user an admin:
```bash
php artisan user:make-admin your-email@example.com
```

Create a new admin user:
```bash
php artisan user:make-admin newemail@example.com --password="YourPassword123"
```
