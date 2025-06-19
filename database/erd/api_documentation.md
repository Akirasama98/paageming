# Dokumentasi API Paageming

## Informasi Sistem
- **Nama Sistem**: Paageming (Agricultural Product Management System)
- **Base URL**: `https://localhost:8001/api` atau `https://your-railway-domain.com/api`
- **Format Response**: JSON
- **Authentication**: Bearer Token (custom JWT-like)

---

## 1. Authentication Endpoints

### 1.1 Register User

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Register |
| **URL** | `POST /api/register` |
| **Method** | POST |
| **Type** | JSON |
| **Autentifikasi** | - |
| **Parameters** | `name: string`<br>`email: string`<br>`password: string`<br>`role: string (optional, default: user)` |
| **Return value** | **JSON Success:**<br>```json<br>{<br>  "message": "User registered successfully",<br>  "user": {<br>    "id": 1,<br>    "name": "John Doe",<br>    "email": "john@example.com",<br>    "role": "user"<br>  },<br>  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."<br>}<br>```<br><br>**Gagal:**<br>```json<br>{<br>  "message": "Validation failed",<br>  "errors": {<br>    "email": ["Email already exists"]<br>  }<br>}<br>``` |
| **Keterangan** | Digunakan untuk mendaftar user baru ke sistem |

### 1.2 Login User

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Login |
| **URL** | `POST /api/login` |
| **Method** | POST |
| **Type** | JSON |
| **Autentifikasi** | - |
| **Parameters** | `email: string`<br>`password: string` |
| **Return value** | **JSON Success:**<br>```json<br>{<br>  "message": "Login successful",<br>  "user": {<br>    "id": 1,<br>    "name": "John Doe",<br>    "email": "john@example.com",<br>    "role": "user"<br>  },<br>  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."<br>}<br>```<br><br>**Gagal:**<br>```json<br>{<br>  "message": "Invalid credentials"<br>}<br>``` |
| **Keterangan** | Digunakan untuk login ke sistem |

### 1.3 Logout User

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Logout |
| **URL** | `POST /api/logout` |
| **Method** | POST |
| **Type** | JSON |
| **Autentifikasi** | Bearer Token |
| **Parameters** | - |
| **Return value** | **JSON Success:**<br>```json<br>{<br>  "message": "Logout successful"<br>}<br>``` |
| **Keterangan** | Digunakan untuk logout dari sistem |

---

## 2. User Management Endpoints

### 2.1 Get All Users

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Get Users |
| **URL** | `GET /api/users` |
| **Method** | GET |
| **Type** | JSON |
| **Autentifikasi** | Public (demo purpose) |
| **Parameters** | - |
| **Return value** | **JSON Success:**<br>```json<br>{<br>  "data": [<br>    {<br>      "id": 1,<br>      "name": "Admin User",<br>      "email": "admin@paageming.com",<br>      "role": "admin",<br>      "created_at": "2025-06-17T00:00:00Z"<br>    },<br>    {<br>      "id": 2,<br>      "name": "Test User",<br>      "email": "user@paageming.com",<br>      "role": "user",<br>      "created_at": "2025-06-17T00:00:00Z"<br>    }<br>  ],<br>  "message": "Users retrieved successfully",<br>  "total": 2<br>}<br>``` |
| **Keterangan** | Mengambil daftar semua users (hardcoded untuk demo) |

---

## 3. Category Management Endpoints

### 3.1 Get All Categories

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Get Categories |
| **URL** | `GET /api/categories` |
| **Method** | GET |
| **Type** | JSON |
| **Autentifikasi** | - |
| **Parameters** | - |
| **Return value** | **JSON Success:**<br>```json<br>[<br>  {<br>    "id": "cat1",<br>    "name": "Sayuran",<br>    "description": "Sayuran segar dari petani lokal",<br>    "created_at": "2025-06-17T08:13:36.000000Z",<br>    "updated_at": "2025-06-17T08:13:36.000000Z"<br>  },<br>  {<br>    "id": "cat2",<br>    "name": "Buah-buahan",<br>    "description": "Buah segar berkualitas tinggi",<br>    "created_at": "2025-06-17T08:13:36.000000Z",<br>    "updated_at": "2025-06-17T08:13:36.000000Z"<br>  }<br>]<br>``` |
| **Keterangan** | Mengambil semua kategori produk dari Firebase |

### 3.2 Get Category by ID

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Get Category |
| **URL** | `GET /api/categories/{id}` |
| **Method** | GET |
| **Type** | JSON |
| **Autentifikasi** | - |
| **Parameters** | `id: string (path parameter)` |
| **Return value** | **JSON Success:**<br>```json<br>{<br>  "id": "cat1",<br>  "name": "Sayuran",<br>  "description": "Sayuran segar dari petani lokal",<br>  "created_at": "2025-06-17T08:13:36.000000Z",<br>  "updated_at": "2025-06-17T08:13:36.000000Z"<br>}<br>```<br><br>**Gagal:**<br>```json<br>{<br>  "message": "Category not found"<br>}<br>``` |
| **Keterangan** | Mengambil detail kategori berdasarkan ID |

### 3.3 Create Category

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Create Category |
| **URL** | `POST /api/categories` |
| **Method** | POST |
| **Type** | JSON |
| **Autentifikasi** | Bearer Token (Admin only) |
| **Parameters** | `name: string (required)`<br>`description: string (optional)` |
| **Return value** | **JSON Success:**<br>```json<br>{<br>  "id": "-OSwzLKDAQDPvAtqX9Gu",<br>  "name": "Bumbu Dapur",<br>  "description": "Bumbu dapur segar",<br>  "created_at": "2025-06-17T08:24:34.000000Z",<br>  "updated_at": "2025-06-17T08:24:34.000000Z"<br>}<br>```<br><br>**Gagal:**<br>```json<br>{<br>  "message": "Failed to create category",<br>  "errors": {<br>    "name": ["Name is required"]<br>  }<br>}<br>``` |
| **Keterangan** | Membuat kategori baru (hanya admin) |

### 3.4 Update Category

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Update Category |
| **URL** | `PUT /api/categories/{id}` |
| **Method** | PUT |
| **Type** | JSON |
| **Autentifikasi** | Bearer Token (Admin only) |
| **Parameters** | `id: string (path parameter)`<br>`name: string (optional)`<br>`description: string (optional)` |
| **Return value** | **JSON Success:**<br>```json<br>{<br>  "id": "-OSwzLKDAQDPvAtqX9Gu",<br>  "name": "Bumbu Dapur Premium",<br>  "description": "Bumbu dapur premium berkualitas tinggi",<br>  "created_at": "2025-06-17T08:24:34.000000Z",<br>  "updated_at": "2025-06-17T08:24:48.000000Z"<br>}<br>```<br><br>**Gagal:**<br>```json<br>{<br>  "message": "Category not found"<br>}<br>``` |
| **Keterangan** | Mengupdate kategori yang sudah ada (hanya admin) |

### 3.5 Delete Category

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Delete Category |
| **URL** | `DELETE /api/categories/{id}` |
| **Method** | DELETE |
| **Type** | JSON |
| **Autentifikasi** | Bearer Token (Admin only) |
| **Parameters** | `id: string (path parameter)` |
| **Return value** | **JSON Success:**<br>Status: 204 No Content<br><br>**Gagal:**<br>```json<br>{<br>  "message": "Category not found"<br>}<br>``` |
| **Keterangan** | Menghapus kategori (hanya admin) |

---

## 4. Product Management Endpoints

### 4.1 Get All Products

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Get Products |
| **URL** | `GET /api/products` |
| **Method** | GET |
| **Type** | JSON |
| **Autentifikasi** | - |
| **Parameters** | - |
| **Return value** | **JSON Success:**<br>```json<br>[<br>  {<br>    "id": "prod1",<br>    "category_id": "cat1",<br>    "name": "Kangkung Segar",<br>    "description": "Kangkung segar dari petani lokal",<br>    "price": 5000,<br>    "stock": 100,<br>    "image_url": null,<br>    "created_at": "2025-06-17T08:13:37.000000Z",<br>    "updated_at": "2025-06-17T08:13:37.000000Z"<br>  }<br>]<br>``` |
| **Keterangan** | Mengambil semua produk dari Firebase |

### 4.2 Get Product by ID

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Get Product |
| **URL** | `GET /api/products/{id}` |
| **Method** | GET |
| **Type** | JSON |
| **Autentifikasi** | - |
| **Parameters** | `id: string (path parameter)` |
| **Return value** | **JSON Success:**<br>```json<br>{<br>  "id": "prod1",<br>  "category_id": "cat1",<br>  "name": "Kangkung Segar",<br>  "description": "Kangkung segar dari petani lokal",<br>  "price": 5000,<br>  "stock": 100,<br>  "image_url": null,<br>  "created_at": "2025-06-17T08:13:37.000000Z",<br>  "updated_at": "2025-06-17T08:13:37.000000Z"<br>}<br>```<br><br>**Gagal:**<br>```json<br>{<br>  "message": "Product not found"<br>}<br>``` |
| **Keterangan** | Mengambil detail produk berdasarkan ID |

### 4.3 Create Product

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Create Product |
| **URL** | `POST /api/products` |
| **Method** | POST |
| **Type** | JSON |
| **Autentifikasi** | Bearer Token (Admin only) |
| **Parameters** | `category_id: string (required)`<br>`name: string (required)`<br>`description: string (optional)`<br>`price: number (required)`<br>`stock: number (required)`<br>`image_url: string (optional)` |
| **Return value** | **JSON Success:**<br>```json<br>{<br>  "id": "-OSwzMKDAQDPvAtqX9Gv",<br>  "category_id": "cat1",<br>  "name": "Tomat Segar",<br>  "description": "Tomat segar berkualitas",<br>  "price": 8000,<br>  "stock": 50,<br>  "image_url": null,<br>  "created_at": "2025-06-17T09:00:00.000000Z",<br>  "updated_at": "2025-06-17T09:00:00.000000Z"<br>}<br>```<br><br>**Gagal:**<br>```json<br>{<br>  "message": "Validation failed",<br>  "errors": {<br>    "name": ["Name is required"],<br>    "price": ["Price must be greater than 0"]<br>  }<br>}<br>``` |
| **Keterangan** | Membuat produk baru (hanya admin) |

### 4.4 Update Product

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Update Product |
| **URL** | `PUT /api/products/{id}` |
| **Method** | PUT |
| **Type** | JSON |
| **Autentifikasi** | Bearer Token (Admin only) |
| **Parameters** | `id: string (path parameter)`<br>`category_id: string (optional)`<br>`name: string (optional)`<br>`description: string (optional)`<br>`price: number (optional)`<br>`stock: number (optional)`<br>`image_url: string (optional)` |
| **Return value** | **JSON Success:**<br>```json<br>{<br>  "id": "prod1",<br>  "category_id": "cat1",<br>  "name": "Kangkung Super Segar",<br>  "description": "Kangkung super segar dari petani lokal",<br>  "price": 6000,<br>  "stock": 120,<br>  "image_url": "https://example.com/image.jpg",<br>  "created_at": "2025-06-17T08:13:37.000000Z",<br>  "updated_at": "2025-06-17T09:15:00.000000Z"<br>}<br>```<br><br>**Gagal:**<br>```json<br>{<br>  "message": "Product not found"<br>}<br>``` |
| **Keterangan** | Mengupdate produk yang sudah ada (hanya admin) |

### 4.5 Delete Product

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Delete Product |
| **URL** | `DELETE /api/products/{id}` |
| **Method** | DELETE |
| **Type** | JSON |
| **Autentifikasi** | Bearer Token (Admin only) |
| **Parameters** | `id: string (path parameter)` |
| **Return value** | **JSON Success:**<br>Status: 204 No Content<br><br>**Gagal:**<br>```json<br>{<br>  "message": "Product not found"<br>}<br>``` |
| **Keterangan** | Menghapus produk (hanya admin) |

---

## 5. File Upload Endpoints

### 5.1 Upload Image

| **Field** | **Value** |
|-----------|-----------|
| **Nama** | Upload Image |
| **URL** | `POST /api/upload` |
| **Method** | POST |
| **Type** | Multipart/Form-Data |
| **Autentifikasi** | Bearer Token (Admin only) |
| **Parameters** | `file: file (required, image only)`<br>`max_size: 2MB`<br>`allowed_types: jpg, jpeg, png, gif` |
| **Return value** | **JSON Success:**<br>```json<br>{<br>  "message": "File uploaded successfully",<br>  "file": {<br>    "filename": "20250617_secure_filename.jpg",<br>    "original_name": "product_image.jpg",<br>    "size": 1024576,<br>    "mime_type": "image/jpeg",<br>    "url": "/storage/products/20250617_secure_filename.jpg"<br>  }<br>}<br>```<br><br>**Gagal:**<br>```json<br>{<br>  "message": "Validation failed",<br>  "errors": {<br>    "file": ["File must be an image"],<br>    "file": ["File size exceeds 2MB limit"]<br>  }<br>}<br>``` |
| **Keterangan** | Upload gambar produk dengan validasi keamanan (hanya admin) |

---

## 6. Error Codes & Status

| **HTTP Status** | **Description** | **Example Response** |
|-----------------|-----------------|---------------------|
| 200 | OK - Request successful | `{"message": "Success"}` |
| 201 | Created - Resource created successfully | `{"message": "Created", "data": {...}}` |
| 204 | No Content - Delete successful | No response body |
| 400 | Bad Request - Invalid request | `{"message": "Bad request"}` |
| 401 | Unauthorized - Authentication required | `{"message": "Unauthorized"}` |
| 403 | Forbidden - Insufficient permissions | `{"message": "Forbidden"}` |
| 404 | Not Found - Resource not found | `{"message": "Not found"}` |
| 422 | Validation Error - Invalid input data | `{"message": "Validation failed", "errors": {...}}` |
| 500 | Internal Server Error - Server error | `{"message": "Internal server error"}` |

---

## 7. Authentication Headers

Untuk endpoint yang memerlukan autentifikasi, sertakan header berikut:

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
Content-Type: application/json
```

---

## 8. Sample Test Credentials

### Admin User
- **Email**: `admin@paageming.com`
- **Password**: `password`
- **Role**: `admin`

### Regular User
- **Email**: `user@paageming.com`
- **Password**: `password`
- **Role**: `user`

---

## 9. Base URL Examples

### Development
```
http://localhost:8001/api
```

### Production (Railway)
```
https://your-app-name.railway.app/api
```

---

## 10. Additional Information

- **Database**: Firebase Realtime Database
- **File Storage**: Local storage (development) / Firebase Storage (production)
- **API Documentation**: Available at `/api/documentation` (Swagger UI)
- **OpenAPI Spec**: Available at `/docs/api-docs.json`
- **Version**: 1.0
- **Last Updated**: June 17, 2025
