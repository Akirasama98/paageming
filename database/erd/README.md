# Paageming Database Documentation

## Database Architecture Overview

Paageming menggunakan **Firebase Realtime Database** sebagai database utama dengan **Laravel** sebagai backend API. Arsitektur ini dipilih untuk kemudahan deployment dan skalabilitas.

## ERD Files

1. **[Logical ERD](logical_erd.md)** - Menampilkan entitas, atribut, dan relasi dalam perspektif bisnis
2. **[Physical ERD](physical_erd.md)** - Menampilkan implementasi database dalam Firebase dengan struktur JSON
3. **[Visual ERD](visual_erd.md)** - Diagram visual ASCII untuk memudahkan pemahaman relasi
4. **[SQL Schema](schema.sql)** - DDL SQL untuk referensi migrasi ke database relational
5. **[Migration Guide](migration_guide.md)** - Panduan migrasi dari Firebase ke SQL database

## Database Summary

### Entities
- **Users** - Data pengguna (admin & customer)
- **Categories** - Kategori produk pertanian
- **Products** - Produk pertanian yang dijual
- **Carts** - Keranjang belanja
- **Cart Items** - Item dalam keranjang
- **Orders** - Pesanan yang dikonfirmasi
- **Order Items** - Item dalam pesanan

### Key Features
- **NoSQL Structure** - Menggunakan Firebase Realtime Database
- **Real-time Updates** - Data sinkronisasi real-time
- **File Storage** - Firebase Storage untuk gambar produk
- **API-First** - RESTful API dengan Laravel
- **Authentication** - JWT-like token system
- **Role-based Access** - Admin dan User roles

### Database Schema Migration Status

| Entity | Laravel Migration | Firebase Implementation | Status |
|--------|------------------|------------------------|---------|
| Users | ✅ | ✅ | Complete |
| Categories | ✅ | ✅ | Complete |
| Products | ✅ | ✅ | Complete |
| Carts | ✅ | ⏳ | Planned |
| Cart Items | ✅ | ⏳ | Planned |
| Orders | ✅ | ⏳ | Planned |
| Order Items | - | ⏳ | Planned |

## ERD Diagram Preview

```
USER (1) ──────────── (Many) CART ────────── (Many) CART_ITEM
 │                                                       │
 │                                                       │
 └────────── (Many) ORDER ─────────── (Many) ORDER_ITEM ┘
                                                         │
CATEGORY (1) ─────── (Many) PRODUCT ─────────────────────┘
```

## API Endpoints

### Products
- `GET /api/products` - List all products ✅
- `GET /api/products/{id}` - Get product details ✅
- `POST /api/products` - Create product (admin) ✅
- `PUT /api/products/{id}` - Update product (admin) ✅
- `DELETE /api/products/{id}` - Delete product (admin) ✅

### Categories
- `GET /api/categories` - List all categories ✅
- `GET /api/categories/{id}` - Get category details ✅
- `POST /api/categories` - Create category (admin) ✅
- `PUT /api/categories/{id}` - Update category (admin) ✅
- `DELETE /api/categories/{id}` - Delete category (admin) ✅

### Authentication
- `POST /api/register` - User registration ✅
- `POST /api/login` - User login ✅
- `POST /api/logout` - User logout ✅
- `GET /api/users` - List users (public for demo) ✅

### File Upload
- `POST /api/upload` - Upload product images (admin) ✅

### Future Implementation
- `POST /api/cart` - Add to cart ⏳
- `GET /api/cart` - Get cart items ⏳
- `POST /api/checkout` - Checkout process ⏳
- `GET /api/orders` - Get user orders ⏳

## Documentation
- **Swagger UI**: Available at `/api/documentation` ✅
- **API JSON**: Available at `/docs/api-docs.json` ✅

## Development Status

### ✅ Completed
- Database migrasi dari SQLite/MySQL ke Firebase Realtime Database
- Semua CRUD operations untuk Products dan Categories menggunakan Firebase SDK
- Authentication system tanpa database (JWT-like tokens)
- File upload dengan validasi dan security
- Swagger API documentation
- RESTful API endpoints

### ⏳ In Progress / Planned
- Cart and Order management
- Real-time inventory updates
- Advanced product search and filtering
- Payment integration
- Order tracking system

### 🎯 Future Enhancements
- Mobile app integration
- Push notifications
- Analytics dashboard
- Multi-vendor support
- Advanced reporting

## Technical Stack

- **Backend**: Laravel 11
- **Database**: Firebase Realtime Database
- **Storage**: Firebase Storage / Local Storage
- **Authentication**: Custom JWT-like implementation
- **Documentation**: Swagger/OpenAPI 3.0
- **Deployment**: Railway (PaaS)

## Quick Start

1. **Setup Environment**
   ```bash
   cp .env.example .env
   composer install
   php artisan key:generate
   ```

2. **Configure Firebase**
   - Add Firebase credentials to `storage/app/firebase-credentials.json`
   - Set `FIREBASE_DATABASE_URL` in `.env`

3. **Seed Database**
   ```bash
   php artisan firebase:seed
   ```

4. **Start Development Server**
   ```bash
   php artisan serve
   ```

5. **Access API Documentation**
   - Swagger UI: `http://localhost:8000/api/documentation`
