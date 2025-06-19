# Quick API Reference - Paageming

## 🚀 Quick Start

### Base URL
- **Local**: `http://localhost:8000`
- **Production**: `https://your-app.railway.app`

### Test Accounts
```json
Admin: {"email": "admin@example.com", "password": "password"}
User:  {"email": "user@example.com", "password": "password"}
```

## 🔑 Authentication

### 1. Login
```bash
POST /api/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

### 2. Use Token
```bash
Authorization: Bearer {your_token_here}
```

## 📋 Core Endpoints

### Categories
```bash
GET    /api/categories           # List all
GET    /api/categories/{id}      # Get one
POST   /api/categories           # Create (Admin)
PUT    /api/categories/{id}      # Update (Admin)
DELETE /api/categories/{id}      # Delete (Admin)
```

### Products
```bash
GET    /api/products             # List all
GET    /api/products/{id}        # Get one
POST   /api/products             # Create (Admin)
PUT    /api/products/{id}        # Update (Admin)
DELETE /api/products/{id}        # Delete (Admin)
```

### Users
```bash
GET    /api/users                # List all (Admin)
```

### Upload
```bash
POST   /api/upload/image         # Upload image (Admin)
```

## 📝 Quick Test Commands

### 1. Get API Info
```bash
curl http://localhost:8000/
```

### 2. Login Admin
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

### 3. Get Categories
```bash
curl http://localhost:8000/api/categories
```

### 4. Create Category (Admin)
```bash
curl -X POST http://localhost:8000/api/categories \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"Test Category","description":"Test description"}'
```

### 5. Get Products
```bash
curl http://localhost:8000/api/products
```

### 6. Create Product (Admin)
```bash
curl -X POST http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"Test Product","price":10000,"category_id":"cat1","description":"Test product"}'
```

## 🔧 Response Format

### Success Response
```json
{
  "status": "success",
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "message": "Error message",
  "errors": { ... }
}
```

## 📊 HTTP Status Codes
- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `500` - Server Error

## 🎯 Testing Checklist

### Basic Flow
- [ ] Get API info at `/`
- [ ] Login admin
- [ ] Create category
- [ ] Create product
- [ ] Get categories
- [ ] Get products
- [ ] Upload image

### Authentication
- [ ] Register new user
- [ ] Login user
- [ ] Login admin
- [ ] Access protected endpoint
- [ ] Logout

### CRUD Operations
- [ ] Create category
- [ ] Read categories
- [ ] Update category
- [ ] Delete category
- [ ] Create product
- [ ] Read products
- [ ] Update product
- [ ] Delete product

## 🌐 Documentation URLs
- **Swagger UI**: `/api/documentation`
- **Swagger JSON**: `/docs/api-docs.json`
- **API Info**: `/`

## 🚨 Common Issues

### 401 Unauthorized
- Check if token is valid
- Check Authorization header format
- Login again if token expired

### 403 Forbidden
- Check user role (admin required)
- Verify admin token

### 404 Not Found
- Check endpoint URL
- Verify resource ID exists

### 500 Server Error
- Check server logs
- Verify Firebase connection
- Check .env configuration

## 💾 Data Structure

### Category
```json
{
  "id": "string",
  "name": "string",
  "description": "string",
  "created_at": "datetime"
}
```

### Product
```json
{
  "id": "string",
  "name": "string",
  "description": "string",
  "price": "number",
  "category_id": "string",
  "category_name": "string",
  "image_url": "string",
  "stock": "number",
  "created_at": "datetime"
}
```

### User
```json
{
  "id": "string",
  "name": "string",
  "email": "string",
  "role": "user|admin",
  "created_at": "datetime"
}
```

## 🔗 Sample Data

### Sample Category
```json
{
  "name": "Sayuran",
  "description": "Aneka sayuran segar"
}
```

### Sample Product
```json
{
  "name": "Tomat Segar",
  "description": "Tomat merah segar berkualitas",
  "price": 15000,
  "category_id": "cat1",
  "image_url": "https://example.com/tomat.jpg",
  "stock": 100
}
```

## 🛠️ Development Tools

### Postman Collection
Import dari: `/docs/api-docs.json`

### Environment Variables
```
BASE_URL: http://localhost:8000
ADMIN_TOKEN: (from login response)
USER_TOKEN: (from login response)
```

### VS Code REST Client
Create `.http` file:
```http
### Login
POST {{BASE_URL}}/api/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}

### Get Categories
GET {{BASE_URL}}/api/categories
```

## 📁 File Structure
```
/routes/api.php          # API routes
/app/Http/Controllers/Api/ # Controllers
/config/l5-swagger.php   # Swagger config
/storage/api-docs/       # Generated docs
/database/erd/           # Documentation
```

## 🚀 Deployment
- **Railway**: Auto-deploy from Git
- **Environment**: Set .env variables
- **Database**: Firebase (no migration needed)
- **Storage**: Public folder for uploads
