# API Endpoints Table - Paageming Marketplace

## 📋 Implemented Endpoints

| Method | Endpoint | Auth | Role | Description | Status |
|--------|----------|------|------|-------------|--------|
| GET | `/` | ❌ | Public | API Info | ✅ |
| GET | `/api/documentation` | ❌ | Public | Swagger UI | ✅ |
| GET | `/docs/api-docs.json` | ❌ | Public | Swagger JSON | ✅ |

### 🔐 Authentication
| Method | Endpoint | Auth | Role | Description | Request Body | Response |
|--------|----------|------|------|-------------|--------------|----------|
| POST | `/api/register` | ❌ | Public | Register user | name, email, password | user + token |
| POST | `/api/login` | ❌ | Public | Login user/admin | email, password | user + token |
| POST | `/api/logout` | ✅ | User/Admin | Logout | - | success message |

### 📂 Categories
| Method | Endpoint | Auth | Role | Description | Request Body | Response |
|--------|----------|------|------|-------------|--------------|----------|
| GET | `/api/categories` | ❌ | Public | List categories | - | categories array |
| GET | `/api/categories/{id}` | ❌ | Public | Get category | - | category object |
| POST | `/api/categories` | ✅ | Admin | Create category | name, description | created category |
| PUT | `/api/categories/{id}` | ✅ | Admin | Update category | name, description | updated category |
| DELETE | `/api/categories/{id}` | ✅ | Admin | Delete category | - | success message |

### 🛍️ Products
| Method | Endpoint | Auth | Role | Description | Request Body | Response |
|--------|----------|------|------|-------------|--------------|----------|
| GET | `/api/products` | ❌ | Public | List products | - | products array |
| GET | `/api/products/{id}` | ❌ | Public | Get product | - | product object |
| POST | `/api/products` | ✅ | Admin | Create product | name, price, category_id, etc | created product |
| PUT | `/api/products/{id}` | ✅ | Admin | Update product | name, price, stock, etc | updated product |
| DELETE | `/api/products/{id}` | ✅ | Admin | Delete product | - | success message |

### 👥 Users
| Method | Endpoint | Auth | Role | Description | Request Body | Response |
|--------|----------|------|------|-------------|--------------|----------|
| GET | `/api/users` | ✅ | Admin | List users | - | users array |

### 📤 Upload
| Method | Endpoint | Auth | Role | Description | Request Body | Response |
|--------|----------|------|------|-------------|--------------|----------|
| POST | `/api/upload/image` | ✅ | Admin | Upload image | image file | image URL |

## 🔄 Planned Endpoints (Not Yet Implemented)

### 🛒 Cart Management
| Method | Endpoint | Auth | Role | Description | Request Body | Response |
|--------|----------|------|------|-------------|--------------|----------|
| GET | `/api/cart` | ✅ | User | Get user cart | - | cart items array |
| POST | `/api/cart/add` | ✅ | User | Add to cart | product_id, quantity | success message |
| PUT | `/api/cart/update/{item}` | ✅ | User | Update cart item | quantity | updated item |
| DELETE | `/api/cart/remove/{item}` | ✅ | User | Remove from cart | - | success message |
| POST | `/api/cart/checkout` | ✅ | User | Checkout cart | shipping_info | order object |

### 📦 Order Management
| Method | Endpoint | Auth | Role | Description | Request Body | Response |
|--------|----------|------|------|-------------|--------------|----------|
| GET | `/api/orders` | ✅ | User | List user orders | - | orders array |
| GET | `/api/orders/{id}` | ✅ | User | Get order detail | - | order object |
| POST | `/api/orders/{id}/confirm` | ✅ | User | Confirm order | - | success message |
| POST | `/api/orders/{id}/verify` | ✅ | Admin | Verify order | - | success message |
| POST | `/api/orders/{id}/cancel` | ✅ | User/Admin | Cancel order | reason | success message |

### 👤 Profile Management
| Method | Endpoint | Auth | Role | Description | Request Body | Response |
|--------|----------|------|------|-------------|--------------|----------|
| GET | `/api/profile` | ✅ | User | Get profile | - | user object |
| PUT | `/api/profile` | ✅ | User | Update profile | name, phone, address | updated user |
| POST | `/api/profile/change-password` | ✅ | User | Change password | old_password, new_password | success message |

### 🔍 Search & Filter
| Method | Endpoint | Auth | Role | Description | Query Params | Response |
|--------|----------|------|------|-------------|--------------|----------|
| GET | `/api/search` | ❌ | Public | Search products | q, category, min_price, max_price | products array |
| GET | `/api/products/category/{category}` | ❌ | Public | Products by category | limit, page | products array |
| GET | `/api/products/featured` | ❌ | Public | Featured products | limit | products array |

### ⭐ Reviews & Ratings
| Method | Endpoint | Auth | Role | Description | Request Body | Response |
|--------|----------|------|------|-------------|--------------|----------|
| GET | `/api/products/{id}/reviews` | ❌ | Public | Get product reviews | - | reviews array |
| POST | `/api/products/{id}/reviews` | ✅ | User | Add product review | rating, comment | created review |
| PUT | `/api/reviews/{id}` | ✅ | User | Update review | rating, comment | updated review |
| DELETE | `/api/reviews/{id}` | ✅ | User/Admin | Delete review | - | success message |

### 📊 Admin Dashboard
| Method | Endpoint | Auth | Role | Description | Query Params | Response |
|--------|----------|------|------|-------------|--------------|----------|
| GET | `/api/admin/dashboard` | ✅ | Admin | Dashboard stats | - | statistics object |
| GET | `/api/admin/reports` | ✅ | Admin | Sales reports | period, type | report data |
| GET | `/api/admin/orders` | ✅ | Admin | All orders | status, date | orders array |
| GET | `/api/admin/users/stats` | ✅ | Admin | User statistics | - | user stats |

## 📊 Quick Reference

### Status Codes
| Code | Status | Description |
|------|--------|-------------|
| 200 | OK | Request successful |
| 201 | Created | Resource created |
| 400 | Bad Request | Validation error |
| 401 | Unauthorized | Authentication required |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource not found |
| 500 | Server Error | Internal server error |

### Authentication
| Type | Header | Example |
|------|--------|---------|
| Bearer Token | `Authorization: Bearer {token}` | `Authorization: Bearer 1\|abc123...` |

### Content Types
| Type | Value |
|------|-------|
| JSON | `application/json` |
| File Upload | `multipart/form-data` |

### Roles
| Role | Access Level |
|------|-------------|
| Public | No authentication required |
| User | Authenticated users |
| Admin | Admin users only |

### Test Accounts
| Email | Password | Role |
|-------|----------|------|
| `admin@example.com` | `password` | Admin |
| `user@example.com` | `password` | User |

### Base URLs
| Environment | URL |
|-------------|-----|
| Local | `http://localhost:8000` |
| Production | `https://your-app.railway.app` |

## 🧪 Testing Guide

### 1. Authentication Flow
```bash
# 1. Login
POST /api/login
{"email": "admin@example.com", "password": "password"}

# 2. Use token in subsequent requests
Authorization: Bearer {received_token}
```

### 2. CRUD Operations
```bash
# Create Category (Admin)
POST /api/categories
{"name": "Test Category", "description": "Test description"}

# Create Product (Admin)
POST /api/products
{"name": "Test Product", "price": 10000, "category_id": "cat1"}

# Get Products (Public)
GET /api/products
```

### 3. File Upload
```bash
# Upload Image (Admin)
POST /api/upload/image
Content-Type: multipart/form-data
image: [file]
```

## 📝 Implementation Status

### ✅ Completed
- Authentication (register, login, logout)
- Categories CRUD (create, read, update, delete)
- Products CRUD (create, read, update, delete)
- Users management (list users)
- File upload (image upload)
- API documentation (Swagger)
- Error handling
- Firebase integration

### 🔄 In Progress
- Cart management
- Order management
- User profile
- Search functionality

### 📋 Planned
- Reviews and ratings
- Admin dashboard
- Advanced filtering
- Notifications
- Payment integration

## 🔗 Related Files
- `routes/api.php` - Route definitions
- `routes/web.php` - Web routes
- `app/Http/Controllers/Api/` - API controllers
- `storage/api-docs/api-docs.json` - Swagger documentation
- `database/erd/` - Database documentation
