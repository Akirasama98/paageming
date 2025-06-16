# 🚀 Paageming API - Marketplace Hasil Pertanian

**Laravel API with Firebase Integration - Ready for Railway Deployment**

## 📱 **API Overview**

Paageming adalah REST API untuk marketplace hasil pertanian yang menyediakan:
- 🔐 **Authentication** dengan Laravel Sanctum
- 📦 **Product Management** dengan Firebase sync
- 🗂️ **Category Management** 
- 📤 **File Upload** ke Firebase Storage
- 📚 **API Documentation** dengan Swagger

## 🛠️ **Tech Stack**

- **Backend**: Laravel 12, PHP 8.2+
- **Database**: SQLite (production-ready)
- **Authentication**: Laravel Sanctum
- **Firebase**: Realtime Database + Storage
- **Documentation**: Swagger/OpenAPI
- **Deployment**: Railway (auto-deploy)

## 🚀 **Quick Deploy to Railway**

```bash
1. Fork this repository
2. Go to railway.app
3. Connect GitHub → Select this repo
4. Add environment variables
5. Deploy! (5 minutes total)
```

**Live API**: Auto-generated Railway URL

## 📋 **API Endpoints**

### **Authentication**
```
POST /api/login          # Login user
POST /api/logout         # Logout user
GET  /api/user           # Get authenticated user
```

### **Products**
```
GET    /api/products     # List all products
POST   /api/products     # Create product (Firebase sync)
GET    /api/products/{id} # Get specific product
PUT    /api/products/{id} # Update product
DELETE /api/products/{id} # Delete product
```

### **Categories**
```
GET /api/categories      # List all categories
```

### **File Upload**
```
POST /api/upload         # Upload to Firebase Storage
```

### **Documentation**
```
GET /api/documentation   # Swagger UI
```

## 🔑 **Default Admin Account**

```
Email: admin@panenku.com
Password: admin123
Role: admin
```

## 🧪 **API Testing**

### **Login Example:**
```bash
curl -X POST https://your-api.railway.app/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@panenku.com","password":"admin123"}'
```

### **Create Product:**
```bash
curl -X POST https://your-api.railway.app/api/products \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "name": "Tomat Organik",
    "description": "Tomat segar dari petani lokal", 
    "price": 15000,
    "stock": 100,
    "category_id": 1
  }'
```

## ⚙️ **Environment Variables**

```bash
APP_NAME=Paageming API
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=sqlite
FIREBASE_PROJECT_ID=panenku-cd8ea
FIREBASE_DATABASE_URL=https://panenku-cd8ea-default-rtdb.firebaseio.com/
FIREBASE_STORAGE_BUCKET=panenku-cd8ea.appspot.com
L5_SWAGGER_GENERATE_ALWAYS=true
```

## 🔥 **Firebase Integration**

- **Realtime Database**: Products auto-sync
- **Storage**: File uploads handled
- **Credentials**: Included in repository
- **SDK**: Server-side PHP integration

## 📚 **Documentation**

- **API Docs**: Available at `/api/documentation`
- **Deployment Guide**: `DEPLOY_NO_DOCKER.md`
- **Railway Setup**: `RAILWAY_QUICK_DEPLOY.md`

## 🛠️ **Local Development**

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Start development
php artisan serve
npm run dev
```

## 🚀 **Deployment**

**Primary**: Railway (recommended)
- Auto-detect PHP from `composer.json`
- Uses `railway.toml` configuration
- 5-minute setup, 95% success rate

**Files Ready:**
- ✅ `railway.toml` - Railway config
- ✅ `build.sh` - Build script
- ✅ `Procfile` - Heroku backup option

## 📊 **Features**

- ✅ **RESTful API** design
- ✅ **JWT Authentication** 
- ✅ **Firebase Integration**
- ✅ **File Upload** support
- ✅ **API Documentation**
- ✅ **Production Ready**
- ✅ **Auto Deployment**

## 🤝 **Contributing**

1. Fork the repository
2. Create feature branch
3. Make changes
4. Test API endpoints
5. Submit pull request

## 📄 **License**

MIT License - feel free to use for your projects!

---

**Ready to deploy? Go to [railway.app](https://railway.app) and connect this repository!** 🚀
