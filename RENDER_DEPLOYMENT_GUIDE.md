# 🚀 Render Deployment Options - Laravel Paageming

## ⚠️ **RENDER ENVIRONMENT CLARIFICATION**

Render **TIDAK memiliki environment "PHP"** secara eksplisit. Render menggunakan **auto-detection** berdasarkan files di repository.

---

## 🎯 **DEPLOYMENT OPTIONS**

### **Option 1: Auto-Detection (RECOMMENDED)**

Render akan mendeteksi PHP otomatis dari `composer.json`:

#### **Configuration:**
```
Name: paageming-api
Build Command: chmod +x build.sh && ./build.sh
Start Command: php artisan serve --host=0.0.0.0 --port=$PORT
```

#### **How it works:**
- Render sees `composer.json` → detects PHP environment
- Automatically installs PHP 8.x
- Runs our custom build script
- Starts Laravel development server

---

### **Option 2: Docker Deployment (FALLBACK)**

Jika auto-detection gagal, gunakan Docker:

#### **Configuration:**
```
Name: paageming-api
Dockerfile: Dockerfile
Docker Command: [otomatis dari Dockerfile]
```

#### **Benefits:**
- ✅ Complete control over environment
- ✅ PHP 8.2 guaranteed
- ✅ All extensions included
- ✅ Consistent deployment

---

## 📋 **STEP-BY-STEP DEPLOYMENT**

### **1. Connect Repository**
1. Login ke [render.com](https://render.com)
2. Connect GitHub: `Akirasama98/paageming`
3. Pilih "Web Service"

### **2. Configure Service (Try Auto-Detection First)**
```
Service Name: paageming-api
Branch: main
Build Command: chmod +x build.sh && ./build.sh
Start Command: php artisan serve --host=0.0.0.0 --port=$PORT
```

### **3. Add Environment Variables**
```
APP_NAME=Paageming
APP_ENV=production
APP_DEBUG=false
FIREBASE_PROJECT_ID=panenku-cd8ea
FIREBASE_DATABASE_URL=https://panenku-cd8ea-default-rtdb.firebaseio.com/
FIREBASE_STORAGE_BUCKET=panenku-cd8ea.appspot.com
L5_SWAGGER_GENERATE_ALWAYS=true
```

### **4. Deploy!**
- Click "Create Web Service"
- Wait for build (~5-10 minutes)

---

## 🔧 **IF AUTO-DETECTION FAILS**

### **Switch to Docker:**
1. Delete the current service
2. Create new "Web Service"
3. Choose "Docker" instead of auto-detect
4. Use the same repository
5. Render will use our `Dockerfile`

---

## 📊 **Build Process (What Happens)**

### **Auto-Detection Build:**
```bash
1. Render detects composer.json
2. Installs PHP 8.x automatically
3. Runs our build.sh script:
   - Install Composer dependencies
   - Install NPM dependencies
   - Build assets
   - Generate app key
   - Run migrations
   - Seed database
   - Generate Swagger docs
4. Starts: php artisan serve --host=0.0.0.0 --port=$PORT
```

### **Docker Build:**
```bash
1. Uses our Dockerfile
2. PHP 8.2-cli base image
3. Installs all dependencies
4. Copies application code
5. Runs all setup commands
6. Starts application
```

---

## ✅ **SUCCESS INDICATORS**

### **Build Logs Should Show:**
```
🚀 Starting Render Build Process for Laravel...
📦 Installing Composer dependencies...
📦 Installing NPM dependencies...
🏗️ Building assets...
🔑 Generating application key...
⚙️ Optimizing configuration...
🗄️ Creating SQLite database...
🗄️ Running database migrations...
🌱 Seeding database...
📚 Generating API documentation...
🔐 Setting file permissions...
✅ Build process completed successfully!
```

### **Service Running:**
```
Laravel development server started: http://0.0.0.0:PORT
```

---

## 🧪 **Post-Deployment Tests**

1. **API Health Check:**
   ```
   GET https://your-app.onrender.com/api/products
   ```

2. **Swagger Documentation:**
   ```
   https://your-app.onrender.com/api/documentation
   ```

3. **Firebase Test Page:**
   ```
   https://your-app.onrender.com/firebase-test.html
   ```

---

## 🔥 **FILES SUPPORTING DEPLOYMENT**

- ✅ `composer.json` - PHP auto-detection
- ✅ `build.sh` - Custom build script
- ✅ `Dockerfile` - Docker fallback
- ✅ `render.yaml` - Render configuration
- ✅ `.env.example` - Environment template

**Repository 100% ready untuk kedua deployment options!** 🚀
