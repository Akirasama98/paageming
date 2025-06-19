# API Endpoints Detail - Paageming Marketplace

## 🔐 Authentication Endpoints

### POST `/api/register`
**Deskripsi**: Mendaftarkan user baru
**Auth**: Tidak perlu
**Method**: POST
**Content-Type**: application/json

**Request Body**:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response Success (201)**:
```json
{
  "message": "User registered successfully",
  "user": {
    "id": "user123",
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user"
  },
  "token": "1|abc123..."
}
```

### POST `/api/login`
**Deskripsi**: Login user/admin
**Auth**: Tidak perlu
**Method**: POST
**Content-Type**: application/json

**Request Body**:
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

**Response Success (200)**:
```json
{
  "message": "Login successful",
  "user": {
    "id": "admin",
    "name": "Admin",
    "email": "admin@example.com",
    "role": "admin"
  },
  "token": "1|def456..."
}
```

### POST `/api/logout`
**Deskripsi**: Logout user
**Auth**: Bearer Token
**Method**: POST

**Headers**:
```
Authorization: Bearer {token}
```

**Response Success (200)**:
```json
{
  "message": "Logged out successfully"
}
```

## 📂 Category Endpoints

### GET `/api/categories`
**Deskripsi**: Daftar semua kategori
**Auth**: Tidak perlu
**Method**: GET

**Response Success (200)**:
```json
{
  "status": "success",
  "data": [
    {
      "id": "cat1",
      "name": "Sayuran",
      "description": "Aneka sayuran segar",
      "created_at": "2024-01-01T00:00:00Z"
    }
  ]
}
```

### GET `/api/categories/{id}`
**Deskripsi**: Detail kategori
**Auth**: Tidak perlu
**Method**: GET
**Parameters**: id (string) - ID kategori

**Response Success (200)**:
```json
{
  "status": "success",
  "data": {
    "id": "cat1",
    "name": "Sayuran",
    "description": "Aneka sayuran segar",
    "created_at": "2024-01-01T00:00:00Z"
  }
}
```

### POST `/api/categories` 🔐
**Deskripsi**: Tambah kategori baru
**Auth**: Bearer Token (Admin only)
**Method**: POST
**Content-Type**: application/json

**Headers**:
```
Authorization: Bearer {admin_token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "name": "Buah-buahan",
  "description": "Aneka buah segar"
}
```

**Response Success (201)**:
```json
{
  "status": "success",
  "message": "Category created successfully",
  "data": {
    "id": "cat2",
    "name": "Buah-buahan",
    "description": "Aneka buah segar",
    "created_at": "2024-01-01T00:00:00Z"
  }
}
```

### PUT `/api/categories/{id}` 🔐
**Deskripsi**: Update kategori
**Auth**: Bearer Token (Admin only)
**Method**: PUT
**Parameters**: id (string) - ID kategori
**Content-Type**: application/json

**Headers**:
```
Authorization: Bearer {admin_token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "name": "Sayuran Organik",
  "description": "Aneka sayuran organik segar"
}
```

**Response Success (200)**:
```json
{
  "status": "success",
  "message": "Category updated successfully",
  "data": {
    "id": "cat1",
    "name": "Sayuran Organik",
    "description": "Aneka sayuran organik segar",
    "updated_at": "2024-01-01T00:00:00Z"
  }
}
```

### DELETE `/api/categories/{id}` 🔐
**Deskripsi**: Hapus kategori
**Auth**: Bearer Token (Admin only)
**Method**: DELETE
**Parameters**: id (string) - ID kategori

**Headers**:
```
Authorization: Bearer {admin_token}
```

**Response Success (200)**:
```json
{
  "status": "success",
  "message": "Category deleted successfully"
}
```

## 🛍️ Product Endpoints

### GET `/api/products`
**Deskripsi**: Daftar semua produk
**Auth**: Tidak perlu
**Method**: GET

**Query Parameters** (optional):
- `category`: Filter berdasarkan kategori
- `limit`: Jumlah data per halaman
- `page`: Halaman

**Response Success (200)**:
```json
{
  "status": "success",
  "data": [
    {
      "id": "prod1",
      "name": "Tomat Segar",
      "description": "Tomat merah segar",
      "price": 15000,
      "category_id": "cat1",
      "category_name": "Sayuran",
      "image_url": "https://example.com/tomat.jpg",
      "stock": 100,
      "created_at": "2024-01-01T00:00:00Z"
    }
  ]
}
```

### GET `/api/products/{id}`
**Deskripsi**: Detail produk
**Auth**: Tidak perlu
**Method**: GET
**Parameters**: id (string) - ID produk

**Response Success (200)**:
```json
{
  "status": "success",
  "data": {
    "id": "prod1",
    "name": "Tomat Segar",
    "description": "Tomat merah segar berkualitas tinggi",
    "price": 15000,
    "category_id": "cat1",
    "category_name": "Sayuran",
    "image_url": "https://example.com/tomat.jpg",
    "stock": 100,
    "created_at": "2024-01-01T00:00:00Z"
  }
}
```

### POST `/api/products` 🔐
**Deskripsi**: Tambah produk baru
**Auth**: Bearer Token (Admin only)
**Method**: POST
**Content-Type**: application/json

**Headers**:
```
Authorization: Bearer {admin_token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "name": "Kentang",
  "description": "Kentang segar berkualitas",
  "price": 12000,
  "category_id": "cat1",
  "image_url": "https://example.com/kentang.jpg",
  "stock": 50
}
```

**Response Success (201)**:
```json
{
  "status": "success",
  "message": "Product created successfully",
  "data": {
    "id": "prod2",
    "name": "Kentang",
    "description": "Kentang segar berkualitas",
    "price": 12000,
    "category_id": "cat1",
    "image_url": "https://example.com/kentang.jpg",
    "stock": 50,
    "created_at": "2024-01-01T00:00:00Z"
  }
}
```

### PUT `/api/products/{id}` 🔐
**Deskripsi**: Update produk
**Auth**: Bearer Token (Admin only)
**Method**: PUT
**Parameters**: id (string) - ID produk
**Content-Type**: application/json

**Headers**:
```
Authorization: Bearer {admin_token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "name": "Tomat Premium",
  "price": 20000,
  "stock": 150
}
```

**Response Success (200)**:
```json
{
  "status": "success",
  "message": "Product updated successfully",
  "data": {
    "id": "prod1",
    "name": "Tomat Premium",
    "price": 20000,
    "stock": 150,
    "updated_at": "2024-01-01T00:00:00Z"
  }
}
```

### DELETE `/api/products/{id}` 🔐
**Deskripsi**: Hapus produk
**Auth**: Bearer Token (Admin only)
**Method**: DELETE
**Parameters**: id (string) - ID produk

**Headers**:
```
Authorization: Bearer {admin_token}
```

**Response Success (200)**:
```json
{
  "status": "success",
  "message": "Product deleted successfully"
}
```

## 👥 User Endpoints

### GET `/api/users` 🔐
**Deskripsi**: Daftar semua users
**Auth**: Bearer Token (Admin only)
**Method**: GET

**Headers**:
```
Authorization: Bearer {admin_token}
```

**Response Success (200)**:
```json
{
  "status": "success",
  "data": [
    {
      "id": "user1",
      "name": "John Doe",
      "email": "john@example.com",
      "role": "user",
      "created_at": "2024-01-01T00:00:00Z"
    }
  ]
}
```

## 📤 Upload Endpoints

### POST `/api/upload/image` 🔐
**Deskripsi**: Upload gambar produk
**Auth**: Bearer Token (Admin only)
**Method**: POST
**Content-Type**: multipart/form-data

**Headers**:
```
Authorization: Bearer {admin_token}
```

**Request Body (Form Data)**:
```
image: [file] (jpg, jpeg, png, max 2MB)
```

**Response Success (200)**:
```json
{
  "status": "success",
  "message": "Image uploaded successfully",
  "data": {
    "url": "https://example.com/uploads/image123.jpg",
    "filename": "secure_filename_123.jpg",
    "size": 1024000
  }
}
```

## 🌐 Public Endpoints

### GET `/`
**Deskripsi**: Root endpoint - Info API
**Auth**: Tidak perlu
**Method**: GET

**Response Success (200)**:
```json
{
  "message": "Paageming API - Marketplace Hasil Pertanian",
  "version": "1.0.0",
  "documentation": "https://your-domain.com/api/documentation",
  "endpoints": {
    "products": "https://your-domain.com/api/products",
    "categories": "https://your-domain.com/api/categories",
    "auth": {
      "register": "https://your-domain.com/api/register",
      "login": "https://your-domain.com/api/login"
    }
  }
}
```

### GET `/docs/api-docs.json`
**Deskripsi**: Swagger JSON documentation
**Auth**: Tidak perlu
**Method**: GET

**Response**: Raw JSON schema Swagger

### GET `/api/documentation`
**Deskripsi**: Swagger UI
**Auth**: Tidak perlu
**Method**: GET

**Response**: HTML Swagger UI

## 🔧 Error Responses

### 400 Bad Request
```json
{
  "message": "Validation failed",
  "errors": {
    "name": ["The name field is required"]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
  "message": "Unauthorized"
}
```

### 404 Not Found
```json
{
  "message": "Resource not found"
}
```

### 500 Internal Server Error
```json
{
  "message": "Internal server error"
}
```

## 📝 Notes

1. **Authentication**: Gunakan Bearer Token di header Authorization
2. **Admin Token**: Dapatkan dari login dengan email `admin@example.com`, password `password`
3. **User Token**: Dapatkan dari login dengan user biasa atau registrasi
4. **Content-Type**: Gunakan `application/json` kecuali untuk upload file
5. **Base URL**: Local: `http://localhost:8000`, Production: `https://your-app.railway.app`
6. **Rate Limiting**: Belum implemented (bisa ditambahkan)
7. **Pagination**: Belum implemented pada semua endpoint
8. **CORS**: Sudah dikonfigurasi untuk all origins

## 🧪 Testing

**Test dengan Postman/Insomnia**:
1. Import collection dari Swagger JSON
2. Set environment variable untuk base URL dan token
3. Test authentication flow terlebih dahulu
4. Test CRUD operations

**Test dengan Swagger UI**:
1. Buka `/api/documentation`
2. Authorize dengan token
3. Test endpoint langsung dari UI

**Test dengan cURL**:
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Get products
curl -X GET http://localhost:8000/api/products \
  -H "Accept: application/json"

# Create product (admin)
curl -X POST http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"Test Product","price":10000,"category_id":"cat1"}'
```
