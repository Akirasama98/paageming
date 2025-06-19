-- Paageming Database Schema (SQL DDL)
-- Untuk referensi jika ingin migrasi dari Firebase ke SQL Database

-- Create Database
CREATE DATABASE paageming_db;
USE paageming_db;

-- Table: users
CREATE TABLE users (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255),
    role ENUM('admin', 'user') DEFAULT 'user',
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_users_email (email),
    INDEX idx_users_role (role)
);

-- Table: categories
CREATE TABLE categories (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_categories_name (name)
);

-- Table: products
CREATE TABLE products (
    id VARCHAR(36) PRIMARY KEY,
    category_id VARCHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL CHECK (price >= 0),
    stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0),
    image_url VARCHAR(500),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    INDEX idx_products_category (category_id),
    INDEX idx_products_status (status),
    INDEX idx_products_name (name),
    INDEX idx_products_price (price)
);

-- Table: carts
CREATE TABLE carts (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    status ENUM('active', 'checkout') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_carts_user (user_id),
    INDEX idx_carts_status (status),
    UNIQUE KEY unique_active_cart (user_id, status)
);

-- Table: cart_items
CREATE TABLE cart_items (
    id VARCHAR(36) PRIMARY KEY,
    cart_id VARCHAR(36) NOT NULL,
    product_id VARCHAR(36) NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    price_at_time DECIMAL(10,2) NOT NULL CHECK (price_at_time >= 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_cart_items_cart (cart_id),
    INDEX idx_cart_items_product (product_id),
    UNIQUE KEY unique_cart_product (cart_id, product_id)
);

-- Table: orders
CREATE TABLE orders (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL CHECK (total_amount >= 0),
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    payment_method ENUM('cash', 'transfer', 'ewallet') NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_orders_user (user_id),
    INDEX idx_orders_status (status),
    INDEX idx_orders_payment_status (payment_status),
    INDEX idx_orders_created (created_at)
);

-- Table: order_items
CREATE TABLE order_items (
    id VARCHAR(36) PRIMARY KEY,
    order_id VARCHAR(36) NOT NULL,
    product_id VARCHAR(36) NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    price_at_time DECIMAL(10,2) NOT NULL CHECK (price_at_time >= 0),
    subtotal DECIMAL(10,2) NOT NULL CHECK (subtotal >= 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_order_items_order (order_id),
    INDEX idx_order_items_product (product_id)
);

-- Insert sample data
INSERT INTO users (id, name, email, role) VALUES
('user1', 'Admin Panenku', 'admin@panenku.com', 'admin'),
('user2', 'Pembeli Demo', 'user@panenku.com', 'user');

INSERT INTO categories (id, name, description) VALUES
('cat1', 'Sayuran', 'Sayuran segar dari petani lokal'),
('cat2', 'Buah-buahan', 'Buah segar berkualitas tinggi'),
('cat3', 'Biji-bijian', 'Biji-bijian dan serealia'),
('cat4', 'Olahan', 'Produk olahan pertanian');

INSERT INTO products (id, category_id, name, description, price, stock) VALUES
('prod1', 'cat1', 'Kangkung Segar', 'Kangkung segar dari petani lokal', 5000, 100),
('prod2', 'cat1', 'Bayam Hijau', 'Bayam hijau organik', 7000, 80),
('prod3', 'cat1', 'Cabai Merah', 'Cabai merah pedas berkualitas', 25000, 50),
('prod4', 'cat2', 'Pisang Cavendish', 'Pisang cavendish manis', 15000, 60),
('prod5', 'cat2', 'Mangga Harum Manis', 'Mangga harum manis premium', 35000, 40),
('prod6', 'cat2', 'Jeruk Manis', 'Jeruk manis segar', 20000, 70),
('prod7', 'cat3', 'Beras Premium', 'Beras premium kualitas terbaik', 85000, 30),
('prod8', 'cat3', 'Jagung Manis', 'Jagung manis segar', 12000, 90),
('prod9', 'cat4', 'Keripik Singkong', 'Keripik singkong renyah', 18000, 45),
('prod10', 'cat4', 'Abon Sapi', 'Abon sapi berkualitas tinggi', 65000, 25);

-- Views for reporting
CREATE VIEW product_summary AS
SELECT 
    p.id,
    p.name,
    c.name as category_name,
    p.price,
    p.stock,
    p.status,
    p.created_at
FROM products p
JOIN categories c ON p.category_id = c.id;

CREATE VIEW order_summary AS
SELECT 
    o.id,
    u.name as customer_name,
    o.total_amount,
    o.status,
    o.payment_status,
    COUNT(oi.id) as total_items,
    o.created_at
FROM orders o
JOIN users u ON o.user_id = u.id
LEFT JOIN order_items oi ON o.id = oi.order_id
GROUP BY o.id;

-- Triggers for audit trail
DELIMITER //

CREATE TRIGGER product_stock_update 
AFTER UPDATE ON products
FOR EACH ROW
BEGIN
    IF OLD.stock != NEW.stock THEN
        INSERT INTO stock_movements (product_id, old_stock, new_stock, movement_type, created_at)
        VALUES (NEW.id, OLD.stock, NEW.stock, 'manual_update', NOW());
    END IF;
END//

DELIMITER ;

-- Stored procedures
DELIMITER //

CREATE PROCEDURE GetProductsByCategory(IN category_id VARCHAR(36))
BEGIN
    SELECT * FROM products 
    WHERE category_id = category_id AND status = 'active'
    ORDER BY name;
END//

CREATE PROCEDURE CreateOrder(
    IN p_user_id VARCHAR(36),
    IN p_total_amount DECIMAL(12,2),
    IN p_shipping_address TEXT,
    IN p_payment_method VARCHAR(20)
)
BEGIN
    DECLARE order_id VARCHAR(36) DEFAULT UUID();
    
    INSERT INTO orders (id, user_id, total_amount, shipping_address, payment_method)
    VALUES (order_id, p_user_id, p_total_amount, p_shipping_address, p_payment_method);
    
    SELECT order_id as id;
END//

DELIMITER ;
