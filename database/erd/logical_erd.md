# Paageming - Logical ERD (Entity Relationship Diagram)

## Entities and Relationships

### 1. USER
**Purpose**: Menyimpan data pengguna sistem
**Attributes**:
- user_id (PK)
- name
- email
- password
- role (admin/user)
- created_at
- updated_at

### 2. CATEGORY
**Purpose**: Kategorisasi produk pertanian
**Attributes**:
- category_id (PK)
- name
- description
- created_at
- updated_at

### 3. PRODUCT
**Purpose**: Data produk pertanian yang dijual
**Attributes**:
- product_id (PK)
- category_id (FK)
- name
- description
- price
- stock
- image_url
- status (active/inactive)
- created_at
- updated_at

### 4. CART
**Purpose**: Keranjang belanja pengguna
**Attributes**:
- cart_id (PK)
- user_id (FK)
- status (active/checkout)
- created_at
- updated_at

### 5. CART_ITEM
**Purpose**: Item dalam keranjang belanja
**Attributes**:
- cart_item_id (PK)
- cart_id (FK)
- product_id (FK)
- quantity
- price_at_time
- created_at
- updated_at

### 6. ORDER
**Purpose**: Pesanan yang sudah dikonfirmasi
**Attributes**:
- order_id (PK)
- user_id (FK)
- total_amount
- status (pending/processing/shipped/delivered/cancelled)
- shipping_address
- payment_method
- payment_status
- notes
- created_at
- updated_at

### 7. ORDER_ITEM
**Purpose**: Item dalam pesanan
**Attributes**:
- order_item_id (PK)
- order_id (FK)
- product_id (FK)
- quantity
- price_at_time
- subtotal
- created_at
- updated_at

## Relationships

1. **USER → CART**: One to Many
   - Satu user dapat memiliki banyak cart (history cart)
   - cart.user_id → user.user_id

2. **CATEGORY → PRODUCT**: One to Many
   - Satu kategori dapat memiliki banyak produk
   - product.category_id → category.category_id

3. **CART → CART_ITEM**: One to Many
   - Satu cart dapat memiliki banyak cart items
   - cart_item.cart_id → cart.cart_id

4. **PRODUCT → CART_ITEM**: One to Many
   - Satu produk dapat ada di banyak cart items
   - cart_item.product_id → product.product_id

5. **USER → ORDER**: One to Many
   - Satu user dapat memiliki banyak orders
   - order.user_id → user.user_id

6. **ORDER → ORDER_ITEM**: One to Many
   - Satu order dapat memiliki banyak order items
   - order_item.order_id → order.order_id

7. **PRODUCT → ORDER_ITEM**: One to Many
   - Satu produk dapat ada di banyak order items
   - order_item.product_id → product.product_id

## Business Rules

1. Setiap user harus memiliki email yang unique
2. User dengan role 'admin' dapat mengelola semua produk dan kategori
3. User dengan role 'user' hanya dapat melihat produk dan melakukan transaksi
4. Setiap produk harus memiliki kategori
5. Stock produk tidak boleh negatif
6. Price produk harus lebih besar dari 0
7. Satu user hanya boleh memiliki satu cart dengan status 'active'
8. Cart item quantity harus lebih besar dari 0
9. Order status mengikuti flow: pending → processing → shipped → delivered
10. Order yang sudah delivered tidak dapat dibatalkan
