# 🛒 Cart API - Complete Implementation

## Overview
Cart API telah selesai diimplementasi 100% menggunakan Firebase Realtime Database tanpa ketergantungan pada model Laravel atau database SQL. Semua operasi cart (create, read, update, delete) menggunakan custom token validation.

## Features Implemented ✅

### 1. **Get Cart** - `GET /api/cart`
- Menampilkan isi keranjang user dengan detail produk lengkap
- Kalkulasi total harga otomatis
- Response format: items array + total amount

### 2. **Add to Cart** - `POST /api/cart/add`
- Menambahkan produk ke keranjang dengan quantity tertentu
- Auto-increment quantity jika produk sudah ada
- Validasi produk exists di Firebase

### 3. **Update Cart Item** - `PUT /api/cart/update/{productId}`
- Mengubah quantity item tertentu di keranjang
- Validasi minimum quantity = 1

### 4. **Remove Cart Item** - `DELETE /api/cart/remove/{productId}`
- Menghapus item tertentu dari keranjang
- Clean removal dari Firebase

### 5. **Checkout Cart** - `POST /api/cart/checkout`
- Proses checkout yang membuat order baru
- Kalkulasi total dan simpan ke Firebase orders
- Auto-clear cart setelah checkout berhasil

## Authentication
- Menggunakan custom token validation (JWT-like base64 encoded)
- Token berisi: user_id, email, role, expires_at
- Compatible dengan token yang dihasilkan AuthController

## Data Structure

### Cart Structure in Firebase
```json
{
  "carts": {
    "user_id": {
      "items": {
        "product_id": {
          "quantity": 2,
          "added_at": "2025-06-19T01:30:00.000Z"
        }
      }
    }
  }
}
```

### Order Structure in Firebase
```json
{
  "orders": {
    "order_id": {
      "id": "order_2_1750298995",
      "user_id": "2",
      "items": [
        {
          "product_id": "prod1",
          "product_name": "Kangkung Segar",
          "price": 5000,
          "quantity": 5,
          "total": 25000
        }
      ],
      "total": 25000,
      "status": "pending",
      "created_at": "2025-06-19T01:30:00.000Z"
    }
  }
}
```

## API Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/cart` | Get cart contents | ✅ |
| POST | `/api/cart/add` | Add product to cart | ✅ |
| PUT | `/api/cart/update/{productId}` | Update item quantity | ✅ |
| DELETE | `/api/cart/remove/{productId}` | Remove item from cart | ✅ |
| POST | `/api/cart/checkout` | Checkout cart | ✅ |

## Request/Response Examples

### Add to Cart
```bash
curl -X POST "http://localhost:8000/api/cart/add" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"product_id":"prod1","quantity":2}'
```

Response:
```json
{
  "message": "Added to cart",
  "product_id": "prod1",
  "quantity": 2
}
```

### Get Cart
```bash
curl -X GET "http://localhost:8000/api/cart" \
  -H "Authorization: Bearer {token}"
```

Response:
```json
{
  "cart": {
    "items": [
      {
        "product_id": "prod1",
        "product": {
          "name": "Kangkung Segar",
          "price": 5000,
          "description": "Kangkung segar dari petani lokal"
        },
        "quantity": 2,
        "price": 5000,
        "total": 10000
      }
    ],
    "total": 10000,
    "user_id": 2
  }
}
```

### Checkout
```bash
curl -X POST "http://localhost:8000/api/cart/checkout" \
  -H "Authorization: Bearer {token}"
```

Response:
```json
{
  "message": "Checkout successful",
  "order_id": "order_2_1750298995",
  "total": 25000
}
```

## Testing Results ✅

Semua endpoint telah ditest dan berfungsi dengan sempurna:

1. **Login & Token Generation** ✅
   - Login dengan user@paageming.com berhasil
   - Token JWT-like berhasil dihasilkan

2. **Cart Operations** ✅ 
   - Empty cart returns `{"items":[], "total":0}`
   - Add product (prod1, qty:2) berhasil
   - Add product kedua (prod2, qty:1) berhasil
   - Cart total calculation: 5000*2 + 7000*1 = 17000 ✅
   - Update quantity prod1 dari 2 ke 5 berhasil
   - New total: 5000*5 + 7000*1 = 32000 ✅
   - Remove prod2 berhasil
   - Final total: 5000*5 = 25000 ✅

3. **Checkout Process** ✅
   - Checkout berhasil dengan order_id: order_2_1750298995
   - Cart otomatis kosong setelah checkout
   - Order tersimpan di Firebase

## Frontend Integration ✅

Frontend sudah terintegrasi dengan cart API:
- Login/logout functionality
- Add to cart dari product pages
- Cart page dengan update/remove items
- Checkout process

## Error Handling

- **401 Unauthorized**: Token tidak valid/expired
- **404 Not Found**: Product/item tidak ditemukan  
- **400 Bad Request**: Cart kosong saat checkout
- **500 Internal Server Error**: Firebase connection issues

## Next Steps

Cart API sudah fully functional dan ready untuk production. Semua operasi CRUD telah diimplementasi dengan Firebase dan berfungsi sempurna.

**Status: ✅ COMPLETED - CART API FULLY FUNCTIONAL**
