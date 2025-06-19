# Paageming - Physical ERD (Database Schema)

## Database: Firebase Realtime Database + File Storage

### Firebase Realtime Database Structure

```json
{
  "users": {
    "user1": {
      "name": "string",
      "email": "string",
      "role": "admin|user",
      "created_at": "ISO8601 timestamp",
      "updated_at": "ISO8601 timestamp"
    }
  },
  "categories": {
    "cat1": {
      "name": "string",
      "description": "string",
      "created_at": "ISO8601 timestamp",
      "updated_at": "ISO8601 timestamp"
    }
  },
  "products": {
    "prod1": {
      "category_id": "string (FK to categories)",
      "name": "string",
      "description": "string",
      "price": "number",
      "stock": "number",
      "image_url": "string (URL)",
      "status": "active|inactive",
      "created_at": "ISO8601 timestamp",
      "updated_at": "ISO8601 timestamp"
    }
  },
  "carts": {
    "cart1": {
      "user_id": "string (FK to users)",
      "status": "active|checkout",
      "created_at": "ISO8601 timestamp",
      "updated_at": "ISO8601 timestamp"
    }
  },
  "cart_items": {
    "item1": {
      "cart_id": "string (FK to carts)",
      "product_id": "string (FK to products)",
      "quantity": "number",
      "price_at_time": "number",
      "created_at": "ISO8601 timestamp",
      "updated_at": "ISO8601 timestamp"
    }
  },
  "orders": {
    "order1": {
      "user_id": "string (FK to users)",
      "total_amount": "number",
      "status": "pending|processing|shipped|delivered|cancelled",
      "shipping_address": "string",
      "payment_method": "string",
      "payment_status": "pending|paid|failed",
      "notes": "string",
      "created_at": "ISO8601 timestamp",
      "updated_at": "ISO8601 timestamp"
    }
  },
  "order_items": {
    "orderitem1": {
      "order_id": "string (FK to orders)",
      "product_id": "string (FK to products)",
      "quantity": "number",
      "price_at_time": "number",
      "subtotal": "number",
      "created_at": "ISO8601 timestamp",
      "updated_at": "ISO8601 timestamp"
    }
  }
}
```

## File Storage Structure

### Firebase Storage
```
/product-images/
  ├── {product_id}/
  │   ├── main.jpg
  │   ├── gallery_1.jpg
  │   └── gallery_2.jpg
  └── temp/
      └── {timestamp}_{filename}

/user-avatars/
  └── {user_id}/
      └── avatar.jpg
```

### Local Storage (Development)
```
storage/app/public/
├── products/
│   ├── {secure_filename}.jpg
│   ├── {secure_filename}.png
│   └── ...
└── temp/
    └── {timestamp}_{filename}
```

## Data Types and Constraints

### String Fields
- **name**: max 255 characters, required
- **email**: valid email format, unique, required
- **description**: text, optional
- **image_url**: valid URL format, optional
- **shipping_address**: text, required for orders
- **payment_method**: enum(cash, transfer, ewallet)
- **notes**: text, optional

### Number Fields
- **price**: decimal(10,2), min 0, required
- **stock**: integer, min 0, required
- **quantity**: integer, min 1, required
- **total_amount**: decimal(12,2), min 0, required
- **subtotal**: decimal(10,2), min 0, required

### Enum Fields
- **role**: admin, user
- **status** (product): active, inactive
- **status** (cart): active, checkout
- **status** (order): pending, processing, shipped, delivered, cancelled
- **payment_status**: pending, paid, failed

### Timestamp Fields
- **created_at**: ISO8601 format, auto-generated
- **updated_at**: ISO8601 format, auto-updated

## Indexes and Performance

### Firebase Database Rules
```json
{
  "rules": {
    "users": {
      ".indexOn": ["email", "role"]
    },
    "products": {
      ".indexOn": ["category_id", "status", "created_at"]
    },
    "categories": {
      ".indexOn": ["name"]
    },
    "carts": {
      ".indexOn": ["user_id", "status"]
    },
    "orders": {
      ".indexOn": ["user_id", "status", "created_at"]
    }
  }
}
```

## Security Rules

### Authentication Requirements
- Read access: authenticated users
- Write access: role-based (admin vs user)
- Public endpoints: products (read-only), categories (read-only)

### Data Validation
- Email uniqueness enforced at application level
- Price and stock validation at application level
- File upload validation (type, size, malware scan)
- Input sanitization for all user inputs

## Backup and Recovery

### Firebase Automatic Backup
- Daily automated backups
- Point-in-time recovery available
- Export to JSON format for migration

### File Storage Backup
- Images stored in Firebase Storage with CDN
- Local development images in Laravel storage
- Periodic backup to cloud storage recommended
