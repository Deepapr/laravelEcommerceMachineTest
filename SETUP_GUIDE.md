# E-Commerce Application

A complete Laravel 12 e-commerce application with product management, shopping cart, checkout, coupon system, and product import functionality.

## Features

### Task 1 - Core E-Commerce
- ✅ **Login Backend** - User authentication with email and password
- ✅ **Product CRUD** - Create, read, update, delete products with full management
- ✅ **Image Upload** - Support for JPG and PNG images with WEBP conversion
- ✅ **Frontend Product Listing** - Display products on public pages
- ✅ **Shopping Cart** - Add/remove products, update quantities
- ✅ **Coupon System** - Apply coupons with percentage or fixed discounts
- ✅ **Checkout Process** - Multi-step checkout with order creation

### Task 2 - Product Import
- ✅ **CSV/Excel Import** - Bulk import products from files
- ✅ **Image URL Import** - Download and convert images from URLs to WEBP
- ✅ **Master Data** - Auto-create categories, colors, and sizes during import

## Database Tables

### Core Tables
- `users` - User authentication
- `products` - Product information
- `product_images` - Product images (WEBP format)
- `categories` - Product categories
- `colors` - Color master data
- `sizes` - Size master data

### E-Commerce Tables
- `carts` - Shopping carts (user or session-based)
- `cart_items` - Cart line items
- `orders` - Customer orders
- `order_items` - Order line items
- `coupons` - Discount coupons

## Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/MariaDB
- XAMPP (or similar)

### Step 1: Clone and Install Dependencies
```bash
cd C:\xampp\htdocs\ecommerce_mock

# Install PHP dependencies
composer install

# Create .env file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 2: Database Setup
Update your `.env` file with database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_mock
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:
```bash
php artisan migrate
```

### Step 3: Create Storage Link
```bash
php artisan storage:link
```

### Step 4: Create an Admin User (Optional)
```bash
php artisan tinker
# Then run:
User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password')])
```

### Step 5: Start the Application
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Usage Guide

### Admin Features (After Login)

#### Dashboard
- View total products, orders, and revenue
- See recent orders
- Access all admin tools

**URL:** `http://localhost:8000/admin/dashboard`

#### Product Management
- **Create:** Add new products with images, categories, colors, sizes
- **Read:** View all products with pagination
- **Update:** Edit product details and images
- **Delete:** Remove products from system

**URL:** `http://localhost:8000/admin/products`

#### Master Data Management
- **Categories:** Add/delete product categories
- **Colors:** Add/delete color options with hex codes
- **Sizes:** Add/delete size options

**URLs:**
- Categories: `http://localhost:8000/admin/categories`
- Colors: `http://localhost:8000/admin/colors`
- Sizes: `http://localhost:8000/admin/sizes`

#### Coupon Management
- Create discount coupons (percentage or fixed amount)
- Set usage limits and validity periods
- Manage active/inactive coupons

**URL:** `http://localhost:8000/admin/coupons`

#### Product Import
- Upload CSV or Excel files
- Import products with master data
- Download and convert product images from URLs to WEBP

**URL:** `http://localhost:8000/admin/import`

### Customer Features

#### Product Browsing
- View all available products
- Filter by category
- See product details with images

**URL:** `http://localhost:8000/products`

#### Shopping Cart
- Add products to cart
- Update quantities
- Remove products
- Session-based cart (persists during browser session)

**URL:** `http://localhost:8000/cart`

#### Checkout
- Review order summary
- Enter shipping information
- Apply coupon codes
- Place order

**URL:** `http://localhost:8000/checkout`

## Image Handling

### Upload Process
1. **Validation:** Accept only JPG/PNG files (max 5MB each)
2. **Conversion:** Automatically convert to WEBP format
3. **Storage:** Save in `storage/app/public/products/`
4. **Access:** Available via `/storage/` URL

### Import Process
1. **URL Validation:** Verify image URLs are valid
2. **Download:** Fetch images from external URLs
3. **Conversion:** Convert to WEBP format
4. **Storage:** Save converted images locally

## Import File Format

### CSV Template
```csv
product_name,category,color,size,price,quantity,description,sku,image_url
T-Shirt Red,Apparel,Red,M,19.99,100,A comfortable cotton t-shirt,TSH-RED-M,https://example.com/tshirt-red.jpg
Jeans Blue,Apparel,Blue,32,49.99,50,Classic blue denim jeans,JEAN-BLUE-32,https://example.com/jeans-blue.jpg
```

### Required Columns
- `product_name` - Product name
- `price` - Price (numeric)
- `quantity` - Stock quantity (numeric)

### Optional Columns
- `category` - Will be created if doesn't exist
- `color` - Will be created if doesn't exist
- `size` - Will be created if doesn't exist
- `description` - Product description
- `sku` - Stock keeping unit
- `image_url` - URL to product image (JPG/PNG)

## API Routes Summary

### Public Routes
- `GET /` - Home page
- `GET /products` - Product listing
- `GET /products/{product}` - Product details
- `GET /cart` - View shopping cart
- `GET /login` - Login form
- `GET /register` - Registration form

### Cart Routes
- `POST /cart/add` - Add to cart
- `POST /cart/update` - Update quantities
- `POST /cart/remove` - Remove item

### Checkout Routes
- `GET /checkout` - Checkout form
- `POST /checkout` - Process order
- `POST /checkout/validate-coupon` - Validate coupon (AJAX)

### Admin Routes (Authenticated)
- `GET /admin/dashboard` - Dashboard
- `GET /admin/products` - Product list
- `POST /admin/products` - Create product
- `GET /admin/products/{product}/edit` - Edit form
- `PUT /admin/products/{product}` - Update product
- `DELETE /admin/products/{product}` - Delete product
- `GET /admin/categories` - Categories
- `GET /admin/colors` - Colors
- `GET /admin/sizes` - Sizes
- `GET /admin/coupons` - Coupons
- `GET /admin/import` - Import form
- `POST /admin/import` - Process import

## Coupon System

### Types
- **Percentage:** Discount as percentage of order total
- **Fixed:** Discount as fixed dollar amount

### Features
- Minimum purchase amount requirement
- Usage limits (per coupon)
- Validity period (valid from/until dates)
- Active/inactive status

### Example Coupons
- `SAVE10` - 10% off (min $50 purchase)
- `FLAT20` - $20 off (max usage: 100 times)
- `NEWYEAR` - Valid Jan 1 - Dec 31, 2026

## Troubleshooting

### Images Not Uploading
1. Check `storage/app/public` directory permissions
2. Ensure storage link is created: `php artisan storage:link`
3. Check file upload limits in php.ini

### Database Errors
1. Verify database connection in `.env`
2. Ensure database exists
3. Run migrations: `php artisan migrate`

### Import Not Working
1. Check file format (CSV or Excel)
2. Verify header row has exact column names
3. Check file size (max 5MB)
4. Check logs: `storage/logs/laravel.log`

## Testing

Create test data:
```bash
php artisan tinker

# Create category
Category::create(['name' => 'Test Category', 'description' => 'A test category'])

# Create color
Color::create(['name' => 'Red', 'code' => '#FF0000'])

# Create size
Size::create(['name' => 'Medium', 'value' => 'M'])

# Create product
Product::create([
    'name' => 'Test Product',
    'category_id' => 1,
    'color_id' => 1,
    'size_id' => 1,
    'quantity' => 100,
    'price' => 29.99
])

# Create coupon
Coupon::create([
    'code' => 'TEST10',
    'type' => 'percentage',
    'discount_value' => 10,
    'active' => true
])
```

## Architecture

### Directory Structure
```
ecommerce_mock/
├── app/
│   ├── Models/          # Database models
│   ├── Http/Controllers/ # Request handlers
│   ├── Services/        # Business logic
│   └── Providers/       # Service providers
├── database/
│   ├── migrations/      # Database schema
│   ├── seeders/         # Sample data
│   └── factories/       # Test data factories
├── resources/
│   ├── views/           # Blade templates
│   ├── css/             # Stylesheets
│   └── js/              # JavaScript
├── routes/
│   ├── web.php          # Web routes
│   └── console.php      # Console commands
└── storage/
    └── app/public/      # User-uploaded files
```

### Key Services
- **ImageService:** Handles image upload and conversion
- **ProductImportService:** Manages CSV/Excel import process

### Models
- User, Product, ProductImage, Category, Color, Size
- Cart, CartItem, Order, OrderItem, Coupon

## Performance Considerations

- Products and orders are paginated (15 items per page)
- Product images are converted to WEBP for smaller file sizes
- Database queries use eager loading to prevent N+1 problems
- Session-based cart for guests, database-backed for authenticated users

## Security Features

- CSRF protection on all forms
- SQL injection prevention via ORM
- File upload validation (mime types)
- Authentication required for admin functions
- Password hashing using bcrypt

## Future Enhancements

- Payment gateway integration (Stripe, PayPal)
- Email notifications for orders
- Product reviews and ratings
- Wishlist functionality
- Admin order management
- Customer order history
- Product search and filtering
- Multiple images per product variant
- Inventory management
- Tax calculations
- Shipping method selection

## License

MIT License - See LICENSE file for details

## Support

For issues or questions, please check the Laravel documentation at https://laravel.com/docs
