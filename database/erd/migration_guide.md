# Database Migration Guide

## Firebase to SQL Migration Strategy

### Current State: Firebase Realtime Database
- **Advantages**: Real-time updates, scalable, serverless
- **Disadvantages**: Limited querying, no complex joins, vendor lock-in

### Target State: SQL Database (MySQL/PostgreSQL)
- **Advantages**: Complex queries, transactions, data integrity, standard SQL
- **Disadvantages**: Requires server management, scaling complexity

## Migration Steps

### 1. Data Export from Firebase
```bash
# Install Firebase CLI
npm install -g firebase-tools

# Login to Firebase
firebase login

# Export data
firebase database:get / --project panenku-cd8ea > firebase_export.json
```

### 2. Data Transformation Script
```php
<?php
// migrate_firebase_to_sql.php

require_once 'vendor/autoload.php';

class FirebaseToSQLMigrator
{
    private $pdo;
    private $firebaseData;
    
    public function __construct($dbConfig, $firebaseExportFile)
    {
        // Initialize SQL connection
        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']}";
        $this->pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
        
        // Load Firebase export
        $this->firebaseData = json_decode(file_get_contents($firebaseExportFile), true);
    }
    
    public function migrate()
    {
        $this->migrateUsers();
        $this->migrateCategories();
        $this->migrateProducts();
        // Add other entities as needed
    }
    
    private function migrateUsers()
    {
        if (!isset($this->firebaseData['users'])) return;
        
        $stmt = $this->pdo->prepare("
            INSERT INTO users (id, name, email, role, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($this->firebaseData['users'] as $id => $user) {
            $stmt->execute([
                $id,
                $user['name'] ?? '',
                $user['email'] ?? '',
                $user['role'] ?? 'user',
                $user['created_at'] ?? date('Y-m-d H:i:s'),
                $user['updated_at'] ?? date('Y-m-d H:i:s')
            ]);
        }
    }
    
    // ... other migration methods
}

// Usage
$dbConfig = [
    'host' => 'localhost',
    'database' => 'paageming_db',
    'username' => 'root',
    'password' => ''
];

$migrator = new FirebaseToSQLMigrator($dbConfig, 'firebase_export.json');
$migrator->migrate();
```

### 3. Laravel Model Updates
```php
// Update Laravel models to use Eloquent instead of Firebase

// app/Models/Product.php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'description', 
        'price', 'stock', 'image_url', 'status'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
```

### 4. Controller Updates
```php
// Update controllers to use Eloquent instead of Firebase SDK

// app/Http/Controllers/Api/ProductController.php
public function index()
{
    $products = Product::with('category')
        ->where('status', 'active')
        ->paginate(15);
    
    return response()->json($products);
}

public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0'
    ]);
    
    $product = Product::create($request->all());
    
    return response()->json($product, 201);
}
```

### 5. Database Configuration Update
```php
// config/database.php
'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'paageming_db'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
],
```

### 6. Environment Variables Update
```env
# .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paageming_db
DB_USERNAME=root
DB_PASSWORD=

# Remove Firebase configuration
# FIREBASE_CREDENTIALS=
# FIREBASE_DATABASE_URL=
```

## Migration Checklist

### Pre-Migration
- [ ] Backup current Firebase data
- [ ] Set up SQL database server
- [ ] Create database schema using schema.sql
- [ ] Test data transformation scripts
- [ ] Update Laravel models and controllers
- [ ] Update environment configuration

### Migration Process
- [ ] Export data from Firebase
- [ ] Transform and import data to SQL
- [ ] Verify data integrity
- [ ] Update application configuration
- [ ] Run comprehensive tests
- [ ] Deploy updated application

### Post-Migration
- [ ] Monitor application performance
- [ ] Verify all endpoints work correctly
- [ ] Update backup procedures
- [ ] Update monitoring and logging
- [ ] Document new database procedures

## Rollback Plan

### Emergency Rollback
1. Revert application code to Firebase version
2. Update environment variables
3. Restart application services
4. Verify Firebase connectivity

### Data Synchronization
If running both systems temporarily:
```php
// Sync data from SQL back to Firebase
class SQLToFirebaseSyncer
{
    public function syncProducts()
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            $this->firebaseDatabase
                ->getReference('products/' . $product->id)
                ->set($product->toArray());
        }
    }
}
```

## Performance Considerations

### Firebase Advantages
- Auto-scaling
- Real-time updates
- Minimal server management

### SQL Advantages
- Complex queries with JOINs
- ACID transactions
- Data consistency
- Better for analytics and reporting

### Recommendation
- Keep Firebase for real-time features (chat, notifications)
- Use SQL for transactional data (orders, inventory)
- Implement hybrid architecture if needed
