# E-commerce Admin Panel API

This Laravel application provides a comprehensive API for managing an e-commerce website admin panel.

## Features

### 🏪 **Products Management**
- Create, read, update, delete products
- Product categories organization
- Stock management
- Featured products
- Product images and metadata
- SEO optimization fields

### 📦 **Orders Management**
- View all orders with pagination
- Order status management (pending, processing, shipped, delivered, cancelled)
- Payment status tracking
- Order details with customer and items information
- Add notes to orders
- Filter orders by status

### 👥 **Customers Management**
- Customer profiles with full contact information
- Customer order history
- Active/inactive customer status
- Customer address management

### 🎫 **Coupons & Discounts**
- Create discount coupons
- Fixed amount or percentage discounts
- Usage limits and expiration dates
- Minimum order amount requirements
- Toggle coupon status

### 📊 **Dashboard Analytics**
- Total orders, products, customers statistics
- Revenue tracking
- Recent orders overview
- Low stock alerts
- Top selling products
- Order status distribution

## API Endpoints

### Authentication
All admin routes are prefixed with `/api/admin/`

### Dashboard
- `GET /api/admin/dashboard` - Main dashboard data
- `GET /api/admin/dashboard/stats` - Analytics and statistics

### Categories
- `GET /api/admin/categories` - List all categories
- `POST /api/admin/categories` - Create new category
- `GET /api/admin/categories/{id}` - Get specific category
- `PUT /api/admin/categories/{id}` - Update category
- `DELETE /api/admin/categories/{id}` - Delete category

### Products
- `GET /api/admin/products` - List all products (paginated)
- `POST /api/admin/products` - Create new product
- `GET /api/admin/products/{id}` - Get specific product
- `PUT /api/admin/products/{id}` - Update product
- `DELETE /api/admin/products/{id}` - Delete product
- `GET /api/admin/products/category/{categoryId}` - Get products by category
- `PATCH /api/admin/products/{id}/toggle-featured` - Toggle featured status

### Orders
- `GET /api/admin/orders` - List all orders (paginated)
- `GET /api/admin/orders/{id}` - Get specific order
- `PATCH /api/admin/orders/{id}/status` - Update order status
- `PATCH /api/admin/orders/{id}/payment-status` - Update payment status
- `POST /api/admin/orders/{id}/notes` - Add notes to order
- `GET /api/admin/orders/status/{status}` - Filter orders by status

### Customers
- `GET /api/admin/customers` - List all customers (paginated)
- `POST /api/admin/customers` - Create new customer
- `GET /api/admin/customers/{id}` - Get specific customer
- `PUT /api/admin/customers/{id}` - Update customer
- `DELETE /api/admin/customers/{id}` - Delete customer
- `GET /api/admin/customers/{id}/orders` - Get customer's order history

### Coupons
- `GET /api/admin/coupons` - List all coupons (paginated)
- `POST /api/admin/coupons` - Create new coupon
- `GET /api/admin/coupons/{id}` - Get specific coupon
- `PUT /api/admin/coupons/{id}` - Update coupon
- `DELETE /api/admin/coupons/{id}` - Delete coupon
- `PATCH /api/admin/coupons/{id}/toggle-status` - Toggle coupon status

## Database Schema

### Tables Created:
- `categories` - Product categories
- `products` - Product catalog
- `customers` - Customer information
- `orders` - Order records
- `order_items` - Order line items
- `coupons` - Discount coupons

## Response Format

All API responses follow a consistent format:

```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": { ... }
}
```

## Installation & Setup

1. **Database**: Configure your database connection in `.env`
2. **Migrations**: Run `php artisan migrate` to create all tables
3. **Server**: Start with `php artisan serve`
4. **API Access**: All endpoints accessible at `http://localhost:8000/api/admin/`

## Security Notes

⚠️ **Important**: This API does not include authentication middleware. For production use, you should:

1. Add authentication middleware to admin routes
2. Implement role-based permissions
3. Add rate limiting
4. Validate and sanitize all inputs
5. Add CORS configuration for frontend integration

## Next Steps

This admin panel provides the complete backend foundation for an e-commerce website. You can:

1. **Frontend Integration**: Connect with React, Vue, or Angular admin dashboard
2. **Authentication**: Implement admin login system
3. **File Upload**: Add image upload functionality for products
4. **Reporting**: Extend analytics and reporting features
5. **Notifications**: Add email notifications for orders
6. **Inventory**: Add advanced inventory management
7. **Multi-vendor**: Extend to support multiple vendors

## Technologies Used

- **Laravel 12** - PHP Framework
- **MySQL/SQLite** - Database
- **Eloquent ORM** - Database relationships
- **API Resources** - JSON response formatting
- **Form Requests** - Input validation

---

**Status**: ✅ Complete admin panel API ready for frontend integration!