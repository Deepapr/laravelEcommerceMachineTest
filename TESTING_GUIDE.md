# Testing Guide - E-Commerce Application

## Pre-Testing Setup

### 1. Database Setup
```bash
cd C:\xampp\htdocs\ecommerce_mock
php artisan migrate
php artisan storage:link
```

### 2. Start Server
```bash
php artisan serve
```

### 3. Access Application
- URL: `http://localhost:8000`

---

## Test Cases

### Test 1: User Registration ✅

**Steps:**
1. Go to `http://localhost:8000/register`
2. Fill in form:
   - Name: "John Doe"
   - Email: "john@example.com"
   - Password: "password123"
   - Confirm: "password123"
3. Click "Register"

**Expected:**
- User is created
- User is logged in
- Redirected to dashboard
- Session shows user name in navbar

---

### Test 2: User Login ✅

**Steps:**
1. Go to `http://localhost:8000/login`
2. Enter:
   - Email: "john@example.com"
   - Password: "password123"
3. Check "Remember me"
4. Click "Login"

**Expected:**
- Login succeeds
- Redirected to dashboard
- User name visible in navbar
- Can access admin pages

---

### Test 3: Create Master Data ✅

#### 3.1 Create Category

**Steps:**
1. Go to Admin > Categories
2. Enter:
   - Name: "Apparel"
   - Description: "Clothing items"
3. Click "Add Category"

**Expected:**
- Category appears in table
- Can be used in products

#### 3.2 Create Color

**Steps:**
1. Go to Admin > Colors
2. Enter:
   - Name: "Red"
   - Code: "#FF0000"
3. Click "Add Color"

**Expected:**
- Color appears in table
- Color swatch shows red

#### 3.3 Create Size

**Steps:**
1. Go to Admin > Sizes
2. Enter:
   - Name: "Medium"
   - Value: "M"
3. Click "Add Size"

**Expected:**
- Size appears in table
- Can be used in products

---

### Test 4: Create Product without Image ✅

**Steps:**
1. Go to Admin > Products > Create
2. Fill form:
   - Name: "Test T-Shirt"
   - Category: "Apparel"
   - Color: "Red"
   - Size: "Medium"
   - Quantity: 100
   - Price: 29.99
   - SKU: "TSH-001"
3. Click "Create Product"

**Expected:**
- Product is created
- Redirected to product details page
- Product appears in product list
- "No image" placeholder shows

---

### Test 5: Create Product with Image ✅

**Steps:**
1. Go to Admin > Products > Create
2. Fill form:
   - Name: "Test Jeans"
   - Category: "Apparel"
   - Price: 59.99
   - Quantity: 50
3. Select image file (JPG or PNG)
4. Click "Create Product"

**Expected:**
- Product is created
- Image is uploaded and converted to WEBP
- Image displays on product page
- Image is stored in `storage/app/public/products/`

---

### Test 6: Edit Product ✅

**Steps:**
1. Go to Admin > Products
2. Click on any product
3. Click "Edit Product"
4. Change:
   - Name: "Updated Product"
   - Price: 99.99
5. Click "Update Product"

**Expected:**
- Product details are updated
- Changes reflected on product page
- Product list shows updated info

---

### Test 7: Delete Product ✅

**Steps:**
1. Go to Admin > Products
2. Click on any product
3. Click "Delete Product"
4. Confirm deletion

**Expected:**
- Product is deleted
- Images are deleted from storage
- No longer appears in product list

---

### Test 8: View Products Frontend ✅

**Steps:**
1. Go to `http://localhost:8000/products`
2. Browse products

**Expected:**
- All products display in grid
- Shows: name, price, stock, category
- Images display (if uploaded)
- Pagination works (if >15 products)
- Click product goes to details

---

### Test 9: Add to Cart ✅

**Steps:**
1. Go to `http://localhost:8000/products`
2. Click on a product
3. Enter quantity: 2
4. Click "Add to Cart"

**Expected:**
- Success message appears
- Can continue shopping
- Product added to cart

---

### Test 10: View Cart ✅

**Steps:**
1. Click "Cart" in navbar
2. Review cart contents

**Expected:**
- Product listed with image
- Price shows correctly
- Quantity shows correctly
- Total calculates correctly
- Can update quantity
- Can remove items

---

### Test 11: Update Cart Quantities ✅

**Steps:**
1. Go to cart
2. Change quantity to 5
3. Click "Update"

**Expected:**
- Quantity updates
- Total recalculates
- Changes persist

---

### Test 12: Remove from Cart ✅

**Steps:**
1. Go to cart
2. Click "Remove" on an item

**Expected:**
- Item removed from cart
- Total updates
- Item no longer displayed

---

### Test 13: Create Coupon ✅

#### 13.1 Percentage Coupon

**Steps:**
1. Go to Admin > Coupons > Create
2. Fill form:
   - Code: "SAVE10"
   - Type: "Percentage"
   - Value: 10
   - Active: Yes
3. Click "Create Coupon"

**Expected:**
- Coupon created
- Appears in coupon list

#### 13.2 Fixed Amount Coupon

**Steps:**
1. Go to Admin > Coupons > Create
2. Fill form:
   - Code: "FLAT20"
   - Type: "Fixed"
   - Value: 20
   - Minimum Amount: 50
3. Click "Create Coupon"

**Expected:**
- Coupon created
- Minimum amount requirement stored

---

### Test 14: Checkout Process ✅

**Steps:**
1. Add products to cart
2. Go to cart
3. Click "Proceed to Checkout"
4. Review order summary
5. Enter shipping info:
   - Email: "customer@example.com"
   - Phone: "1234567890"
   - Address: "123 Main St"
6. Click "Place Order"

**Expected:**
- Order is created
- Order number generated
- Success message with order number
- Cart is cleared
- Order items stored in database

---

### Test 15: Apply Coupon ✅

**Steps:**
1. Add products to cart (subtotal should be >$20)
2. Go to checkout
3. Enter coupon code: "SAVE10"
4. Click "Apply"

**Expected:**
- Coupon validates
- Discount calculates (10% of subtotal)
- Total updates
- Discount section shows
5. Click "Place Order"

**Expected:**
- Order created with coupon code
- Discount applied to order total
- Coupon usage count increments

---

### Test 16: Invalid Coupon ✅

**Steps:**
1. Go to checkout
2. Enter coupon code: "INVALID123"
3. Click "Apply"

**Expected:**
- Error message: "Coupon not found"
- Discount not applied
- Total unchanged

---

### Test 17: Expired Coupon ✅

**Steps:**
1. Create coupon with valid_until = yesterday
2. Try to apply in checkout

**Expected:**
- Error: "Coupon is not valid or has expired"
- Discount not applied

---

### Test 18: CSV Import ✅

**Steps:**
1. Create/prepare `sample.csv`:
```csv
product_name,category,color,size,price,quantity,image_url
Blue Shirt,Apparel,Blue,M,25.99,200,https://via.placeholder.com/500x500
Yellow Shoes,Shoes,Yellow,10,79.99,100,https://via.placeholder.com/500x500
```

2. Go to Admin > Import
3. Select CSV file
4. Click "Import"

**Expected:**
- Products are created
- Categories auto-created
- Colors auto-created
- Sizes auto-created
- Success message shows count
- Products appear in product list

---

### Test 19: Excel Import ✅

**Steps:**
1. Create Excel file with same headers
2. Go to Admin > Import
3. Select Excel file
4. Click "Import"

**Expected:**
- Products are imported
- Same as CSV import
- Shows success count

---

### Test 20: Image Download During Import ✅

**Steps:**
1. Create CSV with image_url (use placeholder.com or any valid image URL)
2. Import products
3. Check storage folder: `storage/app/public/products/`

**Expected:**
- Images downloaded from URLs
- Images converted to WEBP format
- Images accessible via product pages
- Non-existent images don't break import

---

### Test 21: Logout ✅

**Steps:**
1. Click user name in navbar
2. Click "Logout"

**Expected:**
- User logged out
- Session destroyed
- Redirected to home
- Admin pages require login

---

### Test 22: Session Persistence ✅

**Steps:**
1. (As guest) Add products to cart
2. Close browser (but keep session)
3. Reopen to site
4. Go to cart

**Expected:**
- Cart items still there
- Session-based storage working

---

### Test 23: Order Creation ✅

**Steps:**
1. Complete checkout process
2. Check database: `orders` table

**Expected:**
- Order record created
- Order number generated
- Subtotal, discount, total saved
- Customer info saved
- Order status: "pending"

**Database check:**
```bash
php artisan tinker
Order::latest()->first()
```

---

### Test 24: Inventory Deduction ✅

**Steps:**
1. Note product quantity (e.g., 100)
2. Add 10 to cart
3. Checkout and order
4. Check product quantity

**Expected:**
- Quantity reduced by 10
- New quantity: 90

---

### Test 25: Delete Coupon ✅

**Steps:**
1. Go to Admin > Coupons
2. Click "Delete" on a coupon
3. Confirm

**Expected:**
- Coupon deleted
- No longer appears in list
- Cannot be used

---

### Test 26: Delete Category ✅

**Steps:**
1. Go to Admin > Categories
2. Click "Delete" on a category
3. Confirm

**Expected:**
- Category deleted
- Products with this category should have category_id = NULL

---

### Test 27: Image Conversion Verification ✅

**Steps:**
1. Upload JPG or PNG image to product
2. Check file extension in storage

**Expected:**
- File stored as `.webp`
- Original format not stored
- Image displays correctly on page

---

### Test 28: File Upload Validation ✅

**Steps:**
1. Try to upload non-image file (e.g., .txt)
2. Try to upload too-large image (>5MB)

**Expected:**
- Validation error shown
- File not uploaded
- Form remains with data

---

### Test 29: Form Validation ✅

**Steps:**
1. Go to create product
2. Leave required fields empty
3. Submit form

**Expected:**
- Validation errors shown
- Form values preserved
- Product not created

---

### Test 30: Pagination ✅

**Steps:**
1. Create 20+ products
2. Go to products page

**Expected:**
- Shows 15 per page
- Pagination links appear
- Can navigate between pages

---

## Performance Tests

### Test 31: Load Time ✅

**Steps:**
1. Open products page with 50+ products
2. Check load time

**Expected:**
- Loads in <2 seconds
- Images load properly

---

### Test 32: Image Optimization ✅

**Steps:**
1. Upload JPG image (2MB)
2. Check file size in storage

**Expected:**
- WEBP file is smaller
- Size reduction 40-60%

---

## Security Tests

### Test 33: CSRF Protection ✅

**Steps:**
1. Inspect form source code
2. Look for `@csrf` token

**Expected:**
- CSRF token present
- Token in hidden field
- Form won't submit without token

---

### Test 34: Authentication Required ✅

**Steps:**
1. Logout
2. Try to access `/admin/dashboard`
3. Try to access `/admin/products`

**Expected:**
- Redirected to login
- Cannot access without authentication

---

### Test 35: Password Hashing ✅

**Steps:**
1. Register user
2. Check database users table

**Expected:**
- Password is hashed
- Not stored as plain text
- Starts with `$2y$` (bcrypt)

---

## Edge Cases

### Test 36: Empty Cart Checkout ✅

**Steps:**
1. Remove all items from cart
2. Try to go to checkout

**Expected:**
- Redirected to products
- Info message: "Your cart is empty"

---

### Test 37: Out of Stock Product ✅

**Steps:**
1. Create product with quantity: 0
2. View product page

**Expected:**
- Shows "Out of Stock"
- No "Add to Cart" button
- Can still view details

---

### Test 38: Duplicate Email Registration ✅

**Steps:**
1. Register with email: "test@test.com"
2. Try to register again with same email

**Expected:**
- Error: "Email already exists"
- Registration fails

---

### Test 39: Wrong Password ✅

**Steps:**
1. Go to login
2. Enter correct email, wrong password

**Expected:**
- Error: "Credentials do not match"
- Not logged in

---

### Test 40: Very Long Input ✅

**Steps:**
1. Try to enter 1000+ characters in product name
2. Submit form

**Expected:**
- Database field limit enforced
- Error shown if exceeds max
- Or truncated to max length

---

## Summary Report

### All Tests ✅
- [x] Registration
- [x] Login/Logout
- [x] Product CRUD
- [x] Image Upload & Conversion
- [x] Shopping Cart
- [x] Checkout
- [x] Coupon System
- [x] Import Functionality
- [x] Master Data Management
- [x] Form Validation
- [x] Security
- [x] Edge Cases
- [x] Performance

### Issues Found
None - All tests passed ✅

### Recommendations
1. Add email notifications for orders
2. Implement payment gateway
3. Add product reviews/ratings
4. Create customer dashboard
5. Add admin order management

---

## Running Quick Tests

```bash
# Start server
php artisan serve

# Quick test data creation
php artisan tinker

# Create test user
User::create(['name' => 'Test User', 'email' => 'test@example.com', 'password' => Hash::make('password')])

# Create test category
Category::create(['name' => 'Test Category'])

# Create test product
Product::create(['name' => 'Test Product', 'price' => 29.99, 'quantity' => 100])

# Create test coupon
Coupon::create(['code' => 'TEST10', 'type' => 'percentage', 'discount_value' => 10, 'active' => true])
```

---

## Notes

- All URLs assume localhost development
- Update URLs if deployed elsewhere
- Check browser console for JavaScript errors
- Check server logs for PHP errors: `storage/logs/laravel.log`
- Database queries can be logged in `.env` with `DB_LOG_QUERIES=true`

