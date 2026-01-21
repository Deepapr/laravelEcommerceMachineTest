# Admin Account Management

## Overview

- **Regular users** can only register as customers through the registration form
- **Admin accounts** must be created via artisan command or seeder
- Admins cannot shop, only manage products

## Creating Admin Accounts

### Method 1: Using Artisan Command (Recommended)

Make an existing user an admin:
```bash
php artisan user:make-admin your-email@example.com
```

Create a new admin user:
```bash
php artisan user:make-admin newemail@example.com --password="YourPassword123"
```

If you don't provide the password, you'll be prompted to enter it.

### Method 2: Using Tinker (Interactive Shell)

```bash
php artisan tinker
```

Then in the tinker shell:
```php
$user = App\Models\User::create([
    'name' => 'Admin Name',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
exit
```

### Method 3: Direct Database Edit

Connect to your database and run:
```sql
UPDATE users SET role = 'admin' WHERE email = 'user@example.com';
```

## Test Accounts (If Seeded)

After running the seeder, you have:

- **Admin Account:**
  - Email: `admin@example.com`
  - Password: `password`
  - Permissions: Can create, edit, delete products

- **Customer Account:**
  - Email: `customer@example.com`
  - Password: `password`
  - Permissions: Can browse products and checkout


