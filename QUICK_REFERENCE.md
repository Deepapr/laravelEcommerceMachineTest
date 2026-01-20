# E-Commerce Quick Reference Guide

## URLs

### Public Pages
| Page | URL |
|------|-----|
| Home | `http://localhost:8000/` |
| Products | `http://localhost:8000/products` |
| Shopping Cart | `http://localhost:8000/cart` |
| Checkout | `http://localhost:8000/checkout` |
| Login | `http://localhost:8000/login` |
| Register | `http://localhost:8000/register` |

### Admin Pages (After Login)
| Page | URL |
|------|-----|
| Dashboard | `http://localhost:8000/admin/dashboard` |
| Products | `http://localhost:8000/admin/products` |
| Categories | `http://localhost:8000/admin/categories` |
| Colors | `http://localhost:8000/admin/colors` |
| Sizes | `http://localhost:8000/admin/sizes` |
| Coupons | `http://localhost:8000/admin/coupons` |
| Import | `http://localhost:8000/admin/import` |

## Database Tables

| Table | Purpose |
|-------|---------|
| users | User accounts and authentication |
| products | Product information |
| product_images | Product images in WEBP format |
| categories | Product categories |
| colors | Color options |
| sizes | Size options |
| carts | Shopping carts |
| cart_items | Items in carts |
| orders | Customer orders |
| order_items | Items in orders |
| coupons | Discount coupons |

## Key Features

### Product Management
- ✅ Create products with up to 5 images
- ✅ Automatic WEBP conversion for images
- ✅ Edit product details and images
- ✅ Delete products (cascade deletes images)
- ✅ Associate with category, color, size

### Shopping Cart
- ✅ Add products to cart
- ✅ Update quantities
- ✅ Remove items
- ✅ Persistent for authenticated users
- ✅ Session-based for guests

### Checkout
- ✅ Review order summary
- ✅ Enter shipping information
- ✅ Apply coupon codes
- ✅ Validate coupons in real-time
- ✅ Create orders

### Coupons
- ✅ Percentage discounts (e.g., 10% off)
- ✅ Fixed amount discounts (e.g., $20 off)
- ✅ Minimum purchase requirements
- ✅ Usage limits
- ✅ Validity periods
- ✅ Enable/disable status

### Product Import
- ✅ Import from CSV files
- ✅ Import from Excel files
- ✅ Download and convert images from URLs
- ✅ Auto-create categories, colors, sizes
- ✅ Error reporting and logging

## Image Handling

### Upload
1. Select JPG or PNG file
2. System automatically converts to WEBP
3. Saved to `storage/app/public/products/`
4. Accessible via `/storage/` URL

### Import
1. Provide image URL in CSV/Excel
2. System downloads image
3. Converts to WEBP format
4. Saves locally
5. Updates product record

## CSV Import Format

### Headers Required
- `product_name` (required)
- `price` (required)
- `quantity` (required)

### Headers Optional
- `category` - Auto-created if missing
- `color` - Auto-created if missing
- `size` - Auto-created if missing
- `description` - Product description
- `sku` - Stock keeping unit
- `image_url` - Image file URL

### Example
```csv
product_name,category,color,size,price,quantity,image_url
Red T-Shirt,Apparel,Red,M,19.99,100,https://example.com/tshirt.jpg
Blue Jeans,Apparel,Blue,32,49.99,50,https://example.com/jeans.jpg
```

## Admin Commands

### Login
1. Go to `http://localhost:8000/login`
2. Enter email and password
3. Click Login

### Create Product
1. Go to Admin Dashboard
2. Click "Manage Products"
3. Click "Create Product"
4. Fill form and select images
5. Click "Create Product"

### Add Master Data
- **Category:** Admin > Categories > enter name > Add
- **Color:** Admin > Colors > enter name and hex > Add
- **Size:** Admin > Sizes > enter name and value > Add

### Create Coupon
1. Go to Admin > Coupons
2. Click "Create Coupon"
3. Enter code, type, value, dates
4. Click "Create Coupon"

### Import Products
1. Go to Admin > Import
2. Select CSV or Excel file
3. Click "Import"
4. View results and errors

## Customer Actions

### Browse Products
1. Go to `http://localhost:8000/products`
2. View products in grid
3. Click product for details

### Add to Cart
1. On product page, enter quantity
2. Click "Add to Cart"
3. Continue shopping or go to cart

### Checkout
1. Click "Cart" in navbar
2. Review items
3. Click "Proceed to Checkout"
4. Enter shipping info
5. Apply coupon (optional)
6. Click "Place Order"

## Testing Sample Data

### Create Test Category
```
Admin > Categories
Name: Apparel
Description: Clothing and fashion items
Click: Add Category
```

### Create Test Product
```
Admin > Products > Create Product
Name: Test T-Shirt
Category: Apparel
Price: 29.99
Quantity: 100
Upload: Any JPG/PNG image
Click: Create Product
```

### Create Test Coupon
```
Admin > Coupons > Create
Code: TEST10
Type: Percentage
Value: 10
Active: Yes
Click: Create Coupon
```

## Error Handling

### Image Won't Upload
- Check file is JPG or PNG
- Check file size is under 5MB
- Check storage permissions
- Check storage link created: `php artisan storage:link`

### Import Fails
- Check headers match exactly
- Check file is valid CSV/Excel
- Check file size under 5MB
- Check required columns present
- Check logs: `storage/logs/laravel.log`

### Coupon Won't Apply
- Check coupon code is correct
- Check coupon is active
- Check validity dates
- Check minimum amount requirement
- Check usage limit not exceeded

## Performance Tips

- Products paginate 15 per page
- Use categories to filter products
- Convert all images to WEBP
- Use batch import instead of individual products
- Clear browser cache if images not updating

## Security Notes

- All passwords hashed with bcrypt
- CSRF protection on all forms
- File uploads validated
- Admin functions require login
- SQL injection prevention via ORM
- HTML injection prevention via Blade escaping

## File Locations

- Application: `c:\xampp\htdocs\ecommerce_mock\`
- Database: MySQL
- Images: `storage/app/public/products/`
- Logs: `storage/logs/laravel.log`
- Configuration: `.env` file
- Database: `database/migrations/`

## Useful Commands

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link

# Clear cache
php artisan cache:clear

# View logs
tail -f storage/logs/laravel.log

# Tinker console
php artisan tinker
```

## Support Resources

- Laravel Docs: https://laravel.com/docs
- Setup Guide: See `SETUP_GUIDE.md`
- Implementation Details: See `IMPLEMENTATION_SUMMARY.md`
- Sample CSV: See `database/imports/sample_products.csv`
