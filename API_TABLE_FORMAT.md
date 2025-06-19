# API Documentation - Format Tabel

## 1. Authentication API

| No | API | Informasi |
|----|-----|-----------|
| 1. | **Nama:** | Login |
|    | **URL** | https://localhost:8000/api/login |
|    | **Method** | POST |
|    | **Type** | JSON |
|    | **Authentication** | - |
|    | **Parameters** | email: string |
|    |           | password: string |
|    | **Return value** | JSON |
|    |           | Success: |
|    |           | { |
|    |           | "access_token": "eyJ1c2VyX2lkIjoxLCJlbWFpbCI6...", |
|    |           | "token_type": "Bearer", |
|    |           | "user": { |
|    |           | "id": 1, |
|    |           | "name": "Admin User", |
|    |           | "email": "admin@paageming.com", |
|    |           | "role": "admin" |
|    |           | } |
|    |           | } |
|    |           | |
|    |           | Gagal: |
|    |           | { |
|    |           | "message": "Invalid credentials" |
|    |           | } |
|    | **Keterangan** | Digunakan untuk login ke sistem |

| No | API | Informasi |
|----|-----|-----------|
| 2. | **Nama:** | Register |
|    | **URL** | https://localhost:8000/api/register |
|    | **Method** | POST |
|    | **Type** | JSON |
|    | **Authentication** | - |
|    | **Parameters** | name: string |
|    |           | email: string |
|    |           | password: string |
|    | **Return value** | JSON |
|    |           | Success: |
|    |           | { |
|    |           | "access_token": "eyJ1c2VyX2lkIjoxLCJlbWFpbCI6...", |
|    |           | "token_type": "Bearer", |
|    |           | "user": { |
|    |           | "id": 1234567890, |
|    |           | "name": "John Doe", |
|    |           | "email": "user@example.com", |
|    |           | "role": "user" |
|    |           | } |
|    |           | } |
|    |           | |
|    |           | Gagal: |
|    |           | { |
|    |           | "message": "The given data was invalid.", |
|    |           | "errors": { |
|    |           | "email": ["The email field is required."] |
|    |           | } |
|    |           | } |
|    | **Keterangan** | Digunakan untuk register user baru |

| No | API | Informasi |
|----|-----|-----------|
| 3. | **Nama:** | Logout |
|    | **URL** | https://localhost:8000/api/logout |
|    | **Method** | POST |
|    | **Type** | JSON |
|    | **Authentication** | Bearer Token |
|    | **Parameters** | - |
|    | **Return value** | JSON |
|    |           | Success: |
|    |           | { |
|    |           | "message": "Logged out successfully" |
|    |           | } |
|    |           | |
|    |           | Gagal: |
|    |           | { |
|    |           | "message": "Unauthenticated" |
|    |           | } |
|    | **Keterangan** | Digunakan untuk logout dari sistem |

## 2. Categories API

| No | API | Informasi |
|----|-----|-----------|
| 4. | **Nama:** | Get All Categories |
|    | **URL** | https://localhost:8000/api/categories |
|    | **Method** | GET |
|    | **Type** | JSON |
|    | **Authentication** | - |
|    | **Parameters** | - |
|    | **Return value** | JSON |
|    |           | Success: |
|    |           | { |
|    |           | "status": "success", |
|    |           | "data": [ |
|    |           | { |
|    |           | "id": "cat1", |
|    |           | "name": "Sayuran", |
|    |           | "description": "Aneka sayuran segar", |
|    |           | "created_at": "2024-01-01T00:00:00Z" |
|    |           | } |
|    |           | ] |
|    |           | } |
|    |           | |
|    |           | Gagal: |
|    |           | { |
|    |           | "status": "error", |
|    |           | "message": "Failed to fetch categories" |
|    |           | } |
|    | **Keterangan** | Digunakan untuk mengambil semua kategori |

| No | API | Informasi |
|----|-----|-----------|
| 5. | **Nama:** | Get Category by ID |
|    | **URL** | https://localhost:8000/api/categories/{id} |
|    | **Method** | GET |
|    | **Type** | JSON |
|    | **Authentication** | - |
|    | **Parameters** | id: string (path parameter) |
|    | **Return value** | JSON |
|    |           | Success: |
|    |           | { |
|    |           | "status": "success", |
|    |           | "data": { |
|    |           | "id": "cat1", |
|    |           | "name": "Sayuran", |
|    |           | "description": "Aneka sayuran segar", |
|    |           | "created_at": "2024-01-01T00:00:00Z" |
|    |           | } |
|    |           | } |
|    |           | |
|    |           | Gagal: |
|    |           | { |
|    |           | "status": "error", |
|    |           | "message": "Category not found" |
|    |           | } |
|    | **Keterangan** | Digunakan untuk mengambil detail kategori |

| No | API | Informasi |
|----|-----|-----------|
| 6. | **Nama:** | Create Category |
|    | **URL** | https://localhost:8000/api/categories |
|    | **Method** | POST |
|    | **Type** | JSON |
|    | **Authentication** | Bearer Token (Admin) |
|    | **Parameters** | name: string |
|    |           | description: string |
|    | **Return value** | JSON |
|    |           | Success: |
|    |           | { |
|    |           | "status": "success", |
|    |           | "message": "Category created successfully", |
|    |           | "data": { |
|    |           | "id": "cat2", |
|    |           | "name": "Buah-buahan", |
|    |           | "description": "Aneka buah segar", |
|    |           | "created_at": "2024-01-01T00:00:00Z" |
|    |           | } |
|    |           | } |
|    |           | |
|    |           | Gagal: |
|    |           | { |
|    |           | "message": "Validation failed", |
|    |           | "errors": { |
|    |           | "name": ["The name field is required."] |
|    |           | } |
|    |           | } |
|    | **Keterangan** | Digunakan untuk membuat kategori baru (Admin only) |

## 3. Products API

| No | API | Informasi |
|----|-----|-----------|
| 7. | **Nama:** | Get All Products |
|    | **URL** | https://localhost:8000/api/products |
|    | **Method** | GET |
|    | **Type** | JSON |
|    | **Authentication** | - |
|    | **Parameters** | - |
|    | **Return value** | JSON |
|    |           | Success: |
|    |           | { |
|    |           | "status": "success", |
|    |           | "data": [ |
|    |           | { |
|    |           | "id": "prod1", |
|    |           | "name": "Tomat Segar", |
|    |           | "description": "Tomat merah segar", |
|    |           | "price": 15000, |
|    |           | "category_id": "cat1", |
|    |           | "category_name": "Sayuran", |
|    |           | "image_url": "https://example.com/tomat.jpg", |
|    |           | "stock": 100, |
|    |           | "created_at": "2024-01-01T00:00:00Z" |
|    |           | } |
|    |           | ] |
|    |           | } |
|    |           | |
|    |           | Gagal: |
|    |           | { |
|    |           | "status": "error", |
|    |           | "message": "Failed to fetch products" |
|    |           | } |
|    | **Keterangan** | Digunakan untuk mengambil semua produk |

| No | API | Informasi |
|----|-----|-----------|
| 8. | **Nama:** | Get Product by ID |
|    | **URL** | https://localhost:8000/api/products/{id} |
|    | **Method** | GET |
|    | **Type** | JSON |
|    | **Authentication** | - |
|    | **Parameters** | id: string (path parameter) |
|    | **Return value** | JSON |
|    |           | Success: |
|    |           | { |
|    |           | "status": "success", |
|    |           | "data": { |
|    |           | "id": "prod1", |
|    |           | "name": "Tomat Segar", |
|    |           | "description": "Tomat merah segar berkualitas", |
|    |           | "price": 15000, |
|    |           | "category_id": "cat1", |
|    |           | "image_url": "https://example.com/tomat.jpg", |
|    |           | "stock": 100, |
|    |           | "created_at": "2024-01-01T00:00:00Z" |
|    |           | } |
|    |           | } |
|    |           | |
|    |           | Gagal: |
|    |           | { |
|    |           | "status": "error", |
|    |           | "message": "Product not found" |
|    |           | } |
|    | **Keterangan** | Digunakan untuk mengambil detail produk |

| No | API | Informasi |
|----|-----|-----------|
| 9. | **Nama:** | Create Product |
|    | **URL** | https://localhost:8000/api/products |
|    | **Method** | POST |
|    | **Type** | JSON |
|    | **Authentication** | Bearer Token (Admin) |
|    | **Parameters** | name: string |
|    |           | description: string |
|    |           | price: number |
|    |           | category_id: string |
|    |           | image_url: string (optional) |
|    |           | stock: number |
|    | **Return value** | JSON |
|    |           | Success: |
|    |           | { |
|    |           | "status": "success", |
|    |           | "message": "Product created successfully", |
|    |           | "data": { |
|    |           | "id": "prod2", |
|    |           | "name": "Kentang", |
|    |           | "description": "Kentang segar berkualitas", |
|    |           | "price": 12000, |
|    |           | "category_id": "cat1", |
|    |           | "stock": 50, |
|    |           | "created_at": "2024-01-01T00:00:00Z" |
|    |           | } |
|    |           | } |
|    |           | |
|    |           | Gagal: |
|    |           | { |
|    |           | "message": "Validation failed", |
|    |           | "errors": { |
|    |           | "name": ["The name field is required."], |
|    |           | "price": ["The price field is required."] |
|    |           | } |
|    |           | } |
|    | **Keterangan** | Digunakan untuk membuat produk baru (Admin only) |

## 4. Users API

| No | API | Informasi |
|----|-----|-----------|
| 10. | **Nama:** | Get All Users |
|    | **URL** | https://localhost:8000/api/users |
|    | **Method** | GET |
|    | **Type** | JSON |
|    | **Authentication** | Bearer Token (Admin) |
|    | **Parameters** | - |
|    | **Return value** | JSON |
|    |           | Success: |
|    |           | { |
|    |           | "status": "success", |
|    |           | "data": [ |
|    |           | { |
|    |           | "id": "user1", |
|    |           | "name": "John Doe", |
|    |           | "email": "john@example.com", |
|    |           | "role": "user", |
|    |           | "created_at": "2024-01-01T00:00:00Z" |
|    |           | } |
|    |           | ] |
|    |           | } |
|    |           | |
|    |           | Gagal: |
|    |           | { |
|    |           | "message": "Unauthorized" |
|    |           | } |
|    | **Keterangan** | Digunakan untuk mengambil semua users (Admin only) |

## 5. Upload API

| No | API | Informasi |
|----|-----|-----------|
| 11. | **Nama:** | Upload Image |
|    | **URL** | https://localhost:8000/api/upload/image |
|    | **Method** | POST |
|    | **Type** | multipart/form-data |
|    | **Authentication** | Bearer Token (Admin) |
|    | **Parameters** | image: file (jpg, jpeg, png, max 2MB) |
|    | **Return value** | JSON |
|    |           | Success: |
|    |           | { |
|    |           | "status": "success", |
|    |           | "message": "Image uploaded successfully", |
|    |           | "data": { |
|    |           | "url": "https://example.com/uploads/image123.jpg", |
|    |           | "filename": "secure_filename_123.jpg", |
|    |           | "size": 1024000 |
|    |           | } |
|    |           | } |
|    |           | |
|    |           | Gagal: |
|    |           | { |
|    |           | "message": "Validation failed", |
|    |           | "errors": { |
|    |           | "image": ["The image field is required."] |
|    |           | } |
|    |           | } |
|    | **Keterangan** | Digunakan untuk upload gambar produk (Admin only) |

## Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@paageming.com | admin123 |
| User | user@paageming.com | user123 |

## Status Codes

| Code | Status | Description |
|------|--------|-------------|
| 200 | OK | Request berhasil |
| 201 | Created | Resource berhasil dibuat |
| 400 | Bad Request | Request tidak valid |
| 401 | Unauthorized | Perlu authentication |
| 403 | Forbidden | Tidak memiliki permission |
| 404 | Not Found | Resource tidak ditemukan |
| 422 | Unprocessable Entity | Validation error |
| 500 | Internal Server Error | Server error |

## Authentication

Semua endpoint yang memerlukan authentication menggunakan Bearer Token:

```
Authorization: Bearer {access_token}
```

Token didapat dari endpoint login dan berlaku selama 24 jam.
