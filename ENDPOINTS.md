# Daftar Semua Endpoint API - Paageming Marketplace

## Implemented Endpoints (Sudah Tersedia)

### Authentication Endpoints
- **POST** `/api/register` - Register user baru
- **POST** `/api/login` - Login user/admin
- **POST** `/api/logout` - Logout user

### Public Endpoints (Tidak Perlu Auth)
- **GET** `/` - Root endpoint (info API)
- **GET** `/api/categories` - Daftar semua kategori
- **GET** `/api/categories/{id}` - Detail kategori
- **GET** `/api/products` - Daftar semua produk
- **GET** `/api/products/{id}` - Detail produk
- **GET** `/docs/api-docs.json` - Swagger JSON
- **GET** `/api/documentation` - Swagger UI

### Admin Only Endpoints (Perlu Auth + Admin Role)
- **GET** `/api/users` - Daftar semua users
- **POST** `/api/categories` - Tambah kategori baru
- **PUT** `/api/categories/{id}` - Update kategori
- **DELETE** `/api/categories/{id}` - Hapus kategori
- **POST** `/api/products` - Tambah produk baru
- **PUT** `/api/products/{id}` - Update produk
- **DELETE** `/api/products/{id}` - Hapus produk
- **POST** `/api/upload/image` - Upload gambar produk

### Testing Endpoints (Public - Untuk Testing)
- **POST** `/api/products` - Tambah produk (duplikat untuk testing)
- **PUT** `/api/products/{id}` - Update produk (duplikat untuk testing)
- **DELETE** `/api/products/{id}` - Hapus produk (duplikat untuk testing)
- **POST** `/api/categories` - Tambah kategori (duplikat untuk testing)
- **PUT** `/api/categories/{id}` - Update kategori (duplikat untuk testing)
- **DELETE** `/api/categories/{id}` - Hapus kategori (duplikat untuk testing)

## Planned Endpoints (Belum Implemented)

### User Cart Endpoints (Authenticated Users)
- **GET** `/api/cart` - Lihat keranjang belanja user
- **POST** `/api/cart/add` - Tambah item ke keranjang
- **PUT** `/api/cart/update/{item}` - Update quantity item di keranjang
- **DELETE** `/api/cart/remove/{item}` - Hapus item dari keranjang
- **POST** `/api/cart/checkout` - Checkout keranjang (buat order)

### Order Endpoints (Authenticated Users)
- **POST** `/api/orders/{order}/confirm` - Konfirmasi order (user)
- **POST** `/api/orders/{order}/verify` - Verifikasi order (admin only)

### Additional Endpoints (Future Implementation)
- **GET** `/api/orders` - Daftar orders user
- **GET** `/api/orders/{id}` - Detail order
- **POST** `/api/orders/{id}/cancel` - Cancel order
- **GET** `/api/profile` - Profil user
- **PUT** `/api/profile` - Update profil user
- **GET** `/api/admin/dashboard` - Dashboard admin
- **GET** `/api/admin/reports` - Laporan penjualan
- **GET** `/api/search` - Pencarian produk
- **GET** `/api/products/category/{category}` - Produk berdasarkan kategori
- **GET** `/api/products/featured` - Produk unggulan
- **POST** `/api/products/{id}/reviews` - Tambah review produk
- **GET** `/api/products/{id}/reviews` - Daftar review produk

## Endpoint Details

### Authentication Flow
1. **Register** → POST `/api/register`
2. **Login** → POST `/api/login` (dapat token)
3. **Access Protected** → Gunakan token di header Authorization
4. **Logout** → POST `/api/logout`

### Data Flow
1. **Categories** → GET, POST, PUT, DELETE `/api/categories`
2. **Products** → GET, POST, PUT, DELETE `/api/products`
3. **Upload Images** → POST `/api/upload/image`
4. **User Management** → GET `/api/users`

### Role-Based Access
- **Public**: GET categories, products, documentation
- **User**: Cart, orders, profile
- **Admin**: All endpoints + user management

## Notes
- Semua endpoint menggunakan JSON response
- Authentication menggunakan Laravel Sanctum
- Admin middleware untuk endpoint khusus admin
- Upload file menggunakan validation ketat
- Database menggunakan Firebase Realtime Database
- Swagger UI tersedia di `/api/documentation`

## Testing
Semua endpoint yang sudah implemented dapat ditest menggunakan:
- Postman/Insomnia
- Swagger UI di `/api/documentation`
- Manual HTTP requests

## Status
✅ **Implemented**: Auth, Categories, Products, Users, Upload, Documentation
🔄 **Planned**: Cart, Orders, Advanced features
📋 **Available**: Swagger documentation, ERD, API docs
