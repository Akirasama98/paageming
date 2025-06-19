# Panduan Menggunakan dbdiagram.io untuk Paageming ERD

## 🎯 Quick Start

1. **Buka dbdiagram.io**
   - Kunjungi: https://dbdiagram.io/d
   - Klik "Create new diagram"

2. **Copy & Paste Script**
   - Pilih salah satu file `.dbml` yang tersedia
   - Copy seluruh isi file
   - Paste ke editor dbdiagram.io

3. **Generate ERD**
   - Diagram akan otomatis ter-generate
   - Export sebagai PNG, PDF, atau SQL

## 📁 File DBML yang Tersedia

### 1. `dbdiagram_current.dbml` (RECOMMENDED)
- **Isi**: Hanya entitas yang sudah diimplementasi
- **Status**: ✅ Live & Tested
- **Entitas**: Users, Categories, Products
- **Cocok untuk**: Demo current state, documentation

### 2. `dbdiagram_core.dbml`
- **Isi**: Core entities + planned features
- **Status**: Mix (implemented + planned)
- **Entitas**: Users, Categories, Products, Carts, Orders
- **Cocok untuk**: Development planning, roadmap

### 3. `dbdiagram_schema.dbml` (COMPLETE)
- **Isi**: Full database schema dengan fitur tambahan
- **Status**: Complete design (future-ready)
- **Entitas**: All entities + reviews, notifications, images
- **Cocok untuk**: Full system design, enterprise planning

## 🚀 Cara Menggunakan

### Step 1: Pilih File
```bash
# Untuk demo current implementation
cat database/erd/dbdiagram_current.dbml

# Untuk planning & development
cat database/erd/dbdiagram_core.dbml

# Untuk complete system design
cat database/erd/dbdiagram_schema.dbml
```

### Step 2: Copy Content
- Buka file yang dipilih
- Select All (Ctrl+A)
- Copy (Ctrl+C)

### Step 3: Paste ke dbdiagram.io
- Buka https://dbdiagram.io/d
- Hapus content default
- Paste content yang sudah di-copy
- Tunggu diagram ter-generate

### Step 4: Customize & Export
- Arrange layout sesuai kebutuhan
- Add colors atau notes tambahan
- Export ke format yang diinginkan:
  - PNG (untuk presentasi)
  - PDF (untuk dokumentasi)
  - SQL (untuk database creation)

## 🎨 Customization Tips

### Colors untuk Tables
```dbml
Table users [headercolor: #3498db] {
  // User table in blue
}

Table products [headercolor: #2ecc71] {
  // Product table in green
}

Table categories [headercolor: #e74c3c] {
  // Category table in red
}
```

### Add Notes
```dbml
Table products {
  id varchar(36) [primary key, note: 'Firebase generated key']
  name varchar(255) [not null, note: 'Product name like "Kangkung Segar"']
  
  note: 'This table stores agricultural products
  Current implementation uses Firebase Realtime Database
  API endpoints: GET, POST, PUT, DELETE all tested'
}
```

### Relationship Labels
```dbml
Ref: categories.id < products.category_id [note: 'One category has many products']
```

## 📊 Export Options

### 1. PNG Export
- High quality image
- Perfect for presentations
- Transparent background available

### 2. PDF Export
- Professional documentation
- Scalable vector format
- Multiple pages if needed

### 3. SQL Export
- Generate CREATE TABLE statements
- Ready for database implementation
- Includes indexes and constraints

### 4. JSON Export
- Machine-readable format
- For integration with other tools
- Can be imported back to dbdiagram

## 🔧 Advanced Features

### Table Groups
```dbml
TableGroup core_entities {
  users
  categories
  products
}

TableGroup future_features {
  carts
  cart_items
  orders
  order_items
}
```

### Indexes
```dbml
Table products {
  // ... columns ...
  
  indexes {
    category_id [name: 'idx_products_category']
    (name, category_id) [unique, name: 'unique_product_per_category']
  }
}
```

### Enums
```dbml
enum user_role {
  admin
  user
  moderator
}

Table users {
  role user_role [default: 'user']
}
```

## 🎯 Recommended Workflow

1. **Start with Current State**
   - Use `dbdiagram_current.dbml`
   - Show what's actually working

2. **Add Future Plans**
   - Use `dbdiagram_core.dbml`
   - Show development roadmap

3. **Complete Design**
   - Use `dbdiagram_schema.dbml`
   - Show full system architecture

4. **Present & Document**
   - Export as PNG for presentations
   - Export as PDF for documentation
   - Export as SQL for implementation

## 🌟 Pro Tips

- **Use consistent naming**: snake_case for columns, PascalCase for tables
- **Add meaningful notes**: Explain business logic and constraints
- **Color code by module**: Users (blue), Products (green), Orders (orange)
- **Group related tables**: Core, E-commerce, Analytics, etc.
- **Document relationships**: Add notes explaining business rules

## 📱 Sample Output

After pasting the DBML code, you'll get:
- Professional ERD diagram
- Clear table relationships
- Detailed column information
- Exportable in multiple formats
- Shareable URL for collaboration

Perfect for stakeholder presentations, developer documentation, and system planning! 🚀
